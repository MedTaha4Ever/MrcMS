<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marques extends Model
{
    public function model_id()
    {
        return $this->hasMany(Models::class);
    }
    use HasFactory;
}
