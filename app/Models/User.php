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
        'reporting_to', // ZAROORI: Iske bagair Ali ko Farooq assign nahi kar payenge
        'cash_balance', 
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
        ];
    }

    /**
     * HIERARCHY RELATIONSHIPS
     */

    // Ali ka boss kaun hai? (Ali belongs to Farooq)
    public function manager()
    {
        return $this->belongsTo(User::class, 'reporting_to');
    }

    // Farooq ke under kaun kaun staff hai? (Farooq has many subordinates)
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
     * LIVE ACCOUNTING ACCESSORS
     */

    // 1. Total Received: Jitna cash Admin ne assign kiya
    public function getTotalReceivedAttribute()
    {
        return $this->transactions()
            ->where('type', 'assignment')
            ->where('status', 'approved')
            ->sum('amount');
    }

    // 2. Total Spent: Approved expenses
    public function getTotalSpentAttribute()
    {
        return $this->transactions()
            ->where('type', 'expense')
            ->where('status', 'approved')
            ->sum('amount');
    }

    // 3. Calculated Balance: Live calculation
    public function getCalculatedBalanceAttribute()
    {
        return $this->total_received - $this->total_spent;
    }
}