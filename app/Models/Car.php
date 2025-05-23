<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'mat',
        'modele_id',
        'dpc',
        'contract_id',
        'km',
    ];

    public function contract()
    {
        // Assuming 'contract_id' is the foreign key in the 'cars' table
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function modele()
    {
        // Assuming 'modele_id' is the foreign key in the 'cars' table
        return $this->belongsTo(Modele::class, 'modele_id');
    }
}
