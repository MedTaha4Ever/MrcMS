<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marque extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function modeles()
    {
        return $this->hasMany(Modele::class, 'marque_id');
    }
}
