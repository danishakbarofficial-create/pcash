<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Vault;
use App\Models\VaultLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // 1. MASTER LEDGER
    public function ledger(Request $request)
    {
        $query = Transaction::with('user', 'project')->where('status', 'approved');

        if ($request->user_id) { $query->where('user_id', $request->user_id); }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('transaction_date', [$request->from_date, $request->to_date]);
        }

        $allTransactions = $query->latest('transaction_date')->paginate(20);
        $totalInflow = VaultLog::where('type', 'deposit')->sum('amount') ?? 0;
        $totalOutflow = Transaction::where('type', 'assignment')->where('status', 'approved')->sum('amount') ?? 0;
        
        $vault = Vault::first();
        $vaultBalance = $vault ? $vault->total_balance : 0; 
        $users = User::whereIn('role', ['staff', 'manager', 'admin'])->get();

        return view('admin.ledger', compact('allTransactions', 'users', 'totalInflow', 'totalOutflow', 'vaultBalance'));
    }

    // 2. STAFF SUMMARY
    public function staffBalances(Request $request)
    {
        $allStaff = User::where('role', '!=', 'admin')->get(); 
        $query = User::where('role', '!=', 'admin');

        if ($request->filled('user_id')) {
            $query->where('id', $request->user_id);
        }

        $staffData = $query->get();
        return view('admin.staff-balances', compact('staffData', 'allStaff'));
    }

    // 3. HIERARCHY APPROVAL LOGIC
    public function approve($id) 
    {
        $transaction = Transaction::findOrFail($id);
        $user = $transaction->user;
        $currentUser = auth()->user();

        if ($currentUser->role === 'manager' && $transaction->status === 'pending_manager') {
            if ($user->reporting_to == $currentUser->id) {
                $transaction->update(['status' => 'pending_admin']);
                return back()->with('success', 'Approved by Manager. Now pending Admin final settlement.');
            }
            return back()->with('error', 'You are not authorized to approve this user\'s request.');
        }

        if ($currentUser->role === 'admin') {
            return DB::transaction(function () use ($transaction, $user) {
                if($transaction->type === 'assignment') {
                    $vault = Vault::first();
                    if (!$vault || $vault->total_balance < $transaction->amount) {
                        return back()->with('error', 'Not enough balance in Vault!');
                    }
                    $vault->decrement('total_balance', $transaction->amount);
                    $user->increment('cash_balance', $transaction->amount);
                    $user->increment('total_received', $transaction->amount);
                }

                if($transaction->type === 'expense') {
                    if ($user->cash_balance < $transaction->amount) {
                        return back()->with('error', "Staff wallet has insufficient funds!");
                    }
                    $user->decrement('cash_balance', $transaction->amount);
                    $user->increment('total_spent', $transaction->amount);
                }
                
                $transaction->update(['status' => 'approved']);
                return back()->with('success', 'Final approval complete and balances updated.');
            });
        }

        return back()->with('error', 'Permission denied or invalid status.');
    }

    // 4. VAULT PAGE
    public function createCash() 
{
    $vault = Vault::first() ?: new Vault(['total_balance' => 0]);
    $users = User::whereIn('role', ['staff', 'manager'])->get();
    
    // 1. Projects fetch karein
    $projects = Project::all(); 

    $recentCashOut = Transaction::with('user', 'project')
        ->where('type', 'assignment')
        ->where('status', 'approved')
        ->latest()->take(10)->get();

    $vaultLogs = VaultLog::latest()->take(5)->get();
    
    // 2. Compact mein $projects pass karein
    return view('admin.assign-cash', compact('vault', 'users', 'recentCashOut', 'vaultLogs', 'projects'));
}

    // 4.1. ASSIGN CASH
    public function assignCash(Request $request) 
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'amount'           => 'required|numeric|min:1',
            'project_id'       => 'required|exists:projects,id',
            'receiver_receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::findOrFail($request->user_id);
            $vault = Vault::first();
            
            if (!$vault || $vault->total_balance < $request->amount) {
                throw new \Exception('Insufficient vault balance!');
            }

            $path = $request->file('receiver_receipt')->store('receipts/assignments', 'public');

            Transaction::create([
                'user_id'          => $user->id,
                'project_id'       => $request->project_id,
                'amount'           => $request->amount,
                'type'             => 'assignment',
                'status'           => 'approved',
                'transaction_date' => now(),
                'description'      => 'Cash Assigned',
                'receipt_path'     => $path,
            ]);

            $vault->decrement('total_balance', $request->amount);
            $user->increment('cash_balance', $request->amount);
            $user->increment('total_received', $request->amount);
        });

        return back()->with('success', 'Cash assigned successfully.');
    }

    // 5. SUBMIT EXPENSE
    public function store(Request $request) 
    {
        $user = auth()->user();
        $request->validate([
            'category_id'   => 'required', 
            'amount'        => 'required|numeric|min:0.01',
            'description'   => 'required|string',
            'expense_date'  => 'required|date',
            'receipt_photo' => 'required|image|max:5120',
            'project_id'    => 'required|exists:projects,id',
        ]);

        $status = 'pending_admin'; 
        if ($user->role === 'staff' && !empty($user->reporting_to)) {
            $status = 'pending_manager';
        }

        $path = $request->file('receipt_photo')->store('receipts/' . now()->format('Y-m'), 'public');
        $categories = [1 => 'Food', 2 => 'Fuel', 3 => 'Material', 4 => 'Maintenance', 5 => 'Office'];
        $catName = $categories[$request->category_id] ?? 'General';

        Transaction::create([
            'user_id'          => $user->id,
            'project_id'       => $request->project_id,
            'description'      => "[" . $catName . "] " . $request->description,
            'amount'           => $request->amount,
            'type'             => 'expense', 
            'transaction_date' => $request->expense_date, 
            'status'           => $status, 
            'receipt_path'     => $path,
        ]);

        return back()->with('success', 'Request submitted successfully.');
    }

    // 6. VAULT DEPOSIT
    public function storeCash(Request $request) 
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'source' => 'required|string',
            'date'   => 'required|date',
            'proof'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $proofPath = $request->hasFile('proof') ? $request->file('proof')->store('proofs', 'public') : null;
            VaultLog::create([
                'date'   => $request->date,
                'source' => $request->source,
                'amount' => $request->amount,
                'type'   => 'deposit',
                'proof'  => $proofPath
            ]);
            $vault = Vault::firstOrCreate([], ['total_balance' => 0]);
            $vault->increment('total_balance', $request->amount);
        });

        return back()->with('success', 'Vault balance updated!');
    }

    // 7. MY WALLET
    public function myWallet()
    {
        $user = auth()->user();
        $receivedCash = Transaction::where('user_id', $user->id)
            ->where('type', 'expense') 
            ->latest()->get();

        return view('user.wallet', compact('user', 'receivedCash'));
    }

    // 8. REPORTING
public function reporting(Request $request)
{
    $projects = Project::all();
    
    $staffCash = User::where('role', '!=', 'admin')
        ->withSum(['transactions as total_received' => function($q) use ($request) {
            $q->where('type', 'assignment')->where('status', 'approved');
            if ($request->filled('project')) {
                $q->where('project_id', $request->project);
            }
        }], 'amount')
        ->get();

    $query = Transaction::where('type', 'expense')
        ->where('status', 'approved');

    if ($request->filled('project')) {
        $query->where('project_id', $request->project);
    }

    $filteredTransactions = $query->get();

    // Yahan .whereNotNull('category') lagane se NULL keys (Assignments) hata di jayengi
    $categoryData = $filteredTransactions
        ->whereNotNull('category') 
        ->groupBy('category')
        ->map(fn($group) => $group->sum('amount'));

    return view('admin.reporting', compact('categoryData', 'staffCash', 'projects'));
}
    // 9. MY HISTORY
    public function myHistory()
    {
        $user = auth()->user();
        $expenses = Transaction::where('user_id', $user->id)
            ->latest()
            ->paginate(15);
            
        return view('user.history', compact('expenses'));
    }

    public function reject($id) 
    {
        Transaction::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('error', 'Request has been rejected.');
    }
}