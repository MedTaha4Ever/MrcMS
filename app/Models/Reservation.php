<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'car_id',
        'start_date',
        'end_date',
        'status',
        'total_price',
    ];

    /**
     * Get the client that owns the reservation.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the car that is reserved.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
