<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultLog extends Model
{
    use HasFactory;

    protected $table = 'vault_logs';

    protected $fillable = [
        'user_id', 
        'type', 
        'amount', 
        'date', 
        'source', 
        'proof', 
        'note'
    ];

    /**
     * Relationship: Ek log entry kisi ek user (staff) se judi hoti hai
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}