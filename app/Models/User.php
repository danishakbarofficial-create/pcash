<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Transaction; 

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'project_name',
        'cost_center',
        'reporting_to', 
        'cash_balance', 
        'total_spent',   // ZAROORI: Error fix karne ke liye add kiya
        'total_received', // Behtar tracking ke liye
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'cash_balance' => 'decimal:2',
            'total_spent' => 'decimal:2',
        ];
    }

    /**
     * HIERARCHY RELATIONSHIPS
     */

    // Subordinate ka boss (e.g., Ali belongs to Farooq)
    public function manager()
    {
        return $this->belongsTo(User::class, 'reporting_to');
    }

    // Boss ke under staff (e.g., Farooq has many subordinates)
    public function subordinates()
    {
        return $this->hasMany(User::class, 'reporting_to');
    }

    /**
     * ACCOUNTING RELATIONSHIPS
     */

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * LIVE ACCOUNTING ACCESSORS (For Balances Page)
     */

    // 1. Live Total Received calculation
    public function getLiveReceivedAttribute()
    {
        return $this->transactions()
            ->where('type', 'assignment')
            ->where('status', 'approved')
            ->sum('amount');
    }

    // 2. Live Total Spent calculation
    public function getLiveSpentAttribute()
    {
        return $this->transactions()
            ->where('type', 'expense')
            ->where('status', 'approved')
            ->sum('amount');
    }

    // 3. Calculated Balance (Live logic)
    public function getCalculatedBalanceAttribute()
    {
        return $this->live_received - $this->live_spent;
    }
}