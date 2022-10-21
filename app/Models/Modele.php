<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    public function marque_id()
    {
        return $this->hasOne(Marques::class);
    }
    use HasFactory;
}
