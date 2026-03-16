<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'user_id', 
        'project_id', // Naya column yahan add kiya
        'description', 
        'amount', 
        'type', 
        'transaction_date', 
        'status',
        'receipt_path',
    ];

    // User Relationship
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Project Relationship - Ye naya method hai jo controller mein kaam aayega
    public function project() {
        return $this->belongsTo(Project::class);
    }
}