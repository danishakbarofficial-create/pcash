<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id', 
        'category_id',    // <--- Ye missing tha, ab save hoga
        'amount', 
        'description', 
        'receipt_photo', 
        'expense_date',   // <--- Ye bhi add kar diya taake date save ho
        'project_name', 
        'cost_center', 
        'status', 
        'rejection_reason'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}