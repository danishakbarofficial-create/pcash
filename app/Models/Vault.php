<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use HasFactory;

    // Table ka naam confirm karein
    protected $table = 'vaults';

    protected $fillable = ['total_balance'];
}