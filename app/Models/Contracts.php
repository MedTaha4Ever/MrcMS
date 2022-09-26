<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contracts extends Model
{
    public function car_id()
    {
        return $this->hasMany(Cars::class);
    }

    public function client_id()
    {
        return $this->hasMany(Clients::class);
    }
    use HasFactory;
}
