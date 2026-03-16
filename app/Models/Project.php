<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    // Yeh line add karein:
    protected $fillable = ['name', 'location', 'manager_name'];

    // Agar aapne Transaction ke sath relationship banani hai (Optional but Recommended):
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}