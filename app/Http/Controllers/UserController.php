<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vault;
use App\Models\CashAssignment;
use App\Models\VaultLog;
use App\Models\Transaction; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. All Users List
    public function index()
    {
        $users = User::latest()->get();
        $managers = User::where('role', 'manager')->get();
        $admins = User::where('role', 'admin')->get();
        
        return view('admin.users.index', compact('users', 'managers', 'admins'));
    }
public function adminDashboard()
{
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    // UPDATED: Ab ye Audit Log ke liye saari approved transactions uthayega (Assignments aur Expenses dono)
    $expenses = Transaction::with('user')
        ->whereIn('type', ['expense', 'assignment']) 
        ->where('status', 'approved')
        ->latest()
        ->take(10) // Sirf top 10 dikhane ke liye
        ->get();

    $vault = Vault::first();

    return view('admin.dashboard', compact('expenses', 'vault'));
}

    // 2. Store New User (With Explicit Reporting Logic)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', 
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,manager,staff',
            'reporting_to' => 'nullable|exists:users,id', // Added boss validation
            'project_name' => 'nullable|string',
            'cost_center' => 'nullable|string',
        ]);

        // Priority 1: Agar Form se boss select kiya gaya hai
        $bossId = $request->reporting_to;

        // Priority 2: Agar boss select nahi kiya (Direct), to project match karke manager dhoondo
        if (!$bossId && $request->project_name) {
            $autoManager = User::where('role', 'manager')
                               ->where('project_name', $request->project_name)
                               ->first();
            $bossId = $autoManager ? $autoManager->id : null;
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'project_name' => $request->project_name,
            'cost_center' => $request->cost_center,
            'reporting_to' => $bossId, // Yahan boss ID save ho rahi hai
            'cash_balance' => 0,
        ]);

        // Agar naya user khud Manager hai, to us project ke un-assigned staff usko de do
        if ($request->role === 'manager') {
            User::where('project_name', $request->project_name)
                ->where('role', 'staff')
                ->whereNull('reporting_to')
                ->update(['reporting_to' => $newUser->id]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User Created Successfully!');
    }

    // 3. Vault / Cash Assignment Page
    public function vaultIndex(Request $request) 
    {
        $users = User::where('role', '!=', 'admin')->get(); 
        $vault = Vault::first();
        
        if ($request->routeIs('admin.assignList')) {
            return view('admin.assign-cash', compact('users', 'vault'));
        }

        return view('admin.add-cash', compact('users', 'vault')); 
    }

    // 4. Assign Cash Logic
    public function assignCash(Request $request) 
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'receiver_receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $vault = Vault::first();

        if (!$vault || $vault->total_balance < $request->amount) {
            return back()->with('error', 'Insufficient Vault Balance!');
        }

        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->user_id); 
            $amount = $request->amount;
            $path = $request->file('receiver_receipt')->store('cash_receipts', 'public');

            $vault->decrement('total_balance', $amount);
            $user->increment('cash_balance', $amount);

            CashAssignment::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'receiver_receipt' => $path,
            ]);

            Transaction::create([
                'user_id' => $user->id,
                'description' => "Cash Assigned to " . $user->name,
                'amount' => $amount,
                'type' => 'assignment',
                'transaction_date' => now(),
                'status' => 'approved'
            ]);

            VaultLog::create([
                'user_id' => $user->id,
                'amount'  => $amount,
                'type'    => 'disbursement',
                'date'    => now()->toDateString(),
                'source'  => 'ADMIN ASSIGNMENT', 
                'proof'   => $path,
            ]);
            
            DB::commit();
            return back()->with('success', 'SAR ' . number_format($amount, 2) . ' assigned to ' . $user->name);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Transaction Failed: ' . $e->getMessage());
        }
    }

    // 5. Edit User Page (Managers pass kiye taake dropdown chale)
    public function edit($id) 
    { 
        $user = User::findOrFail($id); 
        $managers = User::where('role', 'manager')->get();
        return view('admin.users.edit', compact('user', 'managers')); 
    }
    
    // 6. Update User (Reporting Boss fix)
    public function update(Request $request, $id) 
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,manager,staff',
            'reporting_to' => 'nullable|exists:users,id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->project_name = $request->project_name;
        $user->cost_center = $request->cost_center;
        $user->reporting_to = $request->reporting_to; // Important: Update boss link

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User details and reporting structure updated!');
    }
    
    // 7. Delete User
    public function destroy($id) 
    { 
        User::findOrFail($id)->delete(); 
        return back()->with('success', 'User deleted!'); 
    }
}