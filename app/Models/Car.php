<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    public function contract_id()
    {
        return $this->hasMany(Contracts::class);
    }

    public function model()
    {
        return $this->hasOne(Models::class);
    }
    
}
