<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Models\Transaction; 
use Illuminate\Support\Facades\Route;
use App\Exports\StaffBalancesExport;
use Maatwebsite\Excel\Facades\Excel;

// 1. PUBLIC LANDING
Route::get('/', function () {
    return view('welcome');
});

// 2. UNIVERSAL DASHBOARD LOGIC

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Dropdown ke liye projects fetch karein
    $projects = \App\Models\Project::all();

    if ($user->role === 'admin') {
        $expenses = Transaction::with('user')->latest()->get();
        // Admin dashboard agar alag hai toh wahan bhi bhej sakte hain agar zaroorat ho
        return view('admin.dashboard', compact('expenses', 'projects'));
    } 
    
    if ($user->role === 'manager') {
        return app(TransactionController::class)->managerDashboard();
    }

    // Staff Dashboard logic
    $expenses = Transaction::where('user_id', $user->id)->latest()->take(10)->get();
    
    // Yahan $projects pass karna zaroori tha
    return view('dashboard', compact('expenses', 'projects'));
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. AUTHENTICATED SHARED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-wallet', [TransactionController::class, 'myWallet'])->name('user.wallet');
    Route::get('/my-history', [TransactionController::class, 'myHistory'])->name('my.history');

    // Expense Submission Routes
    Route::post('/transactions/store', [TransactionController::class, 'store'])->name('transactions.store');
    Route::post('/save-expense', [TransactionController::class, 'store'])->name('expenses.store'); 
});

// 4. ADMIN & MANAGER ACTIONS
Route::middleware(['auth'])->group(function () {
    
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/reporting', [TransactionController::class, 'reporting'])->name('reporting');

        // User Management
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::post('/users/store', 'store')->name('users.store');
            Route::get('/users/edit/{id}', 'edit')->name('users.edit');
            Route::post('/users/update/{id}', 'update')->name('users.update');
            Route::delete('/users/delete/{id}', 'destroy')->name('users.delete');
        });

        Route::get('/ledger', [TransactionController::class, 'ledger'])->name('ledger');
        Route::get('/staff-balances', [TransactionController::class, 'staffBalances'])->name('staffBalances');

        // Vault & Cash Routes
        Route::get('/add-cash', [TransactionController::class, 'vaultPage'])->name('addCash');
        Route::post('/store-cash', [TransactionController::class, 'storeCash'])->name('storeCash');
        
        // Aliases for dashboard compatibility
        Route::get('/create-cash', [TransactionController::class, 'vaultPage'])->name('createCash'); 

        Route::get('/assign-cash-list', [TransactionController::class, 'createCash'])->name('assignList');
        Route::post('/assign-cash-process', [TransactionController::class, 'assignCash'])->name('assignCash');

        // Project Management
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store');
        Route::delete('/projects/delete/{id}', [ProjectController::class, 'destroy'])->name('projects.delete');

        // Admin Final Actions
        Route::post('/expense/finalize/{id}', [TransactionController::class, 'approve'])->name('expense.finalize');
        Route::post('/reject/{id}', [TransactionController::class, 'reject'])->name('reject');
    });

    Route::get('/admin/export-excel', function () {
    return Excel::download(new StaffBalancesExport, 'Staff_Financial_Report.xlsx');
})->name('admin.export.excel');

    // Manager Specific Approval Routes
    Route::post('/manager/approve/{id}', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/manager/reject/{id}', [TransactionController::class, 'reject'])->name('transactions.reject');
});

require __DIR__.'/auth.php';