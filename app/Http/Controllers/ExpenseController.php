<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Vault;
use App\Models\VaultLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    // 1. Staff: Submit Expense
    public function store(Request $request)
    {
        // Validation names now match your Blade form exactly
        $request->validate([
            'category'     => 'required|string', // Matches name="category"
            'amount'       => 'required|numeric|min:0.01',
            'description'  => 'required|string',
            'attachment'   => 'required|image|max:2048', // Matches name="attachment"
            'expense_date' => 'required|date'
        ]);

        $user = auth()->user();

        // Check if user has enough balance in their wallet
        if ($user->cash_balance < $request->amount) {
            return back()->with('error', 'Insufficient Balance! Your wallet has only SAR ' . number_format($user->cash_balance, 2));
        }

        // Image upload logic (using the 'attachment' key from form)
        $path = $request->file('attachment')->store('expenses', 'public');

        // Database entry
        Expense::create([
            'user_id'       => $user->id,
            'category_id'   => $request->category, // Storing category string/id
            'amount'        => $request->amount,
            'description'   => $request->description,
            'expense_date'  => $request->expense_date,
            'receipt_photo' => $path,
            'project_name'  => $user->project_name ?? 'N/A',
            'cost_center'   => $user->cost_center ?? 'N/A',
            'status'        => ($user->role === 'manager') ? 'pending_admin' : 'pending_manager'
        ]);

        return back()->with('success', 'Expense submitted successfully!');
    }

    // 2. Manager: Approve
    public function managerApprove($id)
    {
        $expense = Expense::findOrFail($id);
        
        // Ensure the manager is only approving for their staff
        if ($expense->user->reporting_to != auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $expense->update(['status' => 'pending_admin']);
        return back()->with('success', 'Approved! Sent to Admin for final payment.');
    }

    // 3. Admin: Finalize (The "Settle" Step)
    public function adminFinalize($id)
    {
        $expense = Expense::findOrFail($id);

        if ($expense->status !== 'pending_admin') {
            return back()->with('error', 'Manager approval required first.');
        }

        DB::beginTransaction();
        try {
            $user = $expense->user;

            if ($user->cash_balance < $expense->amount) {
                return back()->with('error', 'Staff balance is too low to settle this.');
            }

            // Deduct from staff wallet and mark as approved
            $user->decrement('cash_balance', $expense->amount);
            $expense->update(['status' => 'approved']);

            DB::commit();
            return back()->with('success', 'Transaction Settled and Balance Updated!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}