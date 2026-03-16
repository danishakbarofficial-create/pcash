<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Models\Transaction; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController; // <-- Ye line lazmi add karein

// 1. PUBLIC LANDING
Route::get('/', function () {
    return view('welcome');
});

// 2. UNIVERSAL DASHBOARD LOGIC
Route::get('/dashboard', function () {
    $user = auth()->user();

    // --- ADMIN DASHBOARD ---
    if ($user->role === 'admin') {
        $expenses = Transaction::with('user')
            ->latest()
            ->get();
        return view('admin.dashboard', compact('expenses'));
    } 
    
    // --- MANAGER DASHBOARD ---
    if ($user->role === 'manager') {
        $expenses = Transaction::whereHas('user', function($q) use ($user) {
                $q->where('reporting_to', $user->id);
            })->latest()->get();
        return view('manager.dashboard', compact('expenses'));
    }

    // --- STAFF/USER DASHBOARD ---
    $expenses = Transaction::where('user_id', $user->id)->latest()->take(10)->get();
    return view('dashboard', compact('expenses'));
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. AUTHENTICATED SHARED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-wallet', [TransactionController::class, 'myWallet'])->name('user.wallet');
    
    Route::get('/my-history', [TransactionController::class, 'myHistory'])->name('my.history');

    Route::post('/save-expense', [TransactionController::class, 'store'])->name('expenses.store');
});

// 4. ADMIN & MANAGER ACTIONS
Route::middleware(['auth'])->group(function () {
    
    // --- ADMIN ONLY ROUTES ---
    Route::prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', function() {
            return redirect()->route('dashboard');
        })->name('dashboard');

        Route::get('/reporting', [TransactionController::class, 'reporting'])->name('reporting');

        // User Management
        Route::controller(UserController::class)->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::post('/users/store', 'store')->name('users.store');
            Route::get('/users/edit/{id}', 'edit')->name('users.edit');
            Route::post('/users/update/{id}', 'update')->name('users.update');
            Route::delete('/users/delete/{id}', 'destroy')->name('users.delete');
        });

        // Ledger & Balances
        Route::get('/ledger', [TransactionController::class, 'ledger'])->name('ledger');
        Route::get('/staff-balances', [TransactionController::class, 'staffBalances'])->name('staffBalances');

        // Vault & Cash Assignment (Updated to TransactionController)
        Route::get('/add-cash', [TransactionController::class, 'createCash'])->name('addCash');
        Route::post('/store-cash', [TransactionController::class, 'storeCash'])->name('storeCash');
        
        // Yeh dono routes ab TransactionController se handle honge
        Route::get('/assign-cash-list', [TransactionController::class, 'createCash'])->name('assignList');
        Route::post('/assign-cash-process', [TransactionController::class, 'assignCash'])->name('assignCash');


        // Project Management Routes
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store');
Route::delete('/projects/delete/{id}', [ProjectController::class, 'destroy'])->name('projects.delete');

        // Admin Final Actions
        Route::post('/expense/finalize/{id}', [TransactionController::class, 'approve'])->name('expense.finalize');
        Route::post('/reject/{id}', [TransactionController::class, 'reject'])->name('reject');
    });

    // --- MANAGER ONLY ROUTES ---
    Route::prefix('manager')->name('manager.')->group(function () {
        Route::post('/approve/{id}', [TransactionController::class, 'approve'])->name('approve');
    });
});

require __DIR__.'/auth.php';