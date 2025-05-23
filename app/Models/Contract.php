<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    public function car_id()
    {
        return $this->hasMany(Car::class);
    }

    public function client_id()
    {
        return $this->hasMany(Client::class);
    }
    use HasFactory;
}
