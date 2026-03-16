<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashAssignment extends Model
{
    protected $fillable = ['user_id', 'amount', 'receiver_receipt'];

    // Ye relation zaroori hai taake pata chale ye cash kis user ka hai
    public function user() {
        return $this->belongsTo(User::class);
    }
}
