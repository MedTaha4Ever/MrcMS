<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'mat',
        'modele_id',
        'dpc',
        'contract_id',
        'km',
        'price_per_day',
        'status',
        'image_url'
    ];

    protected $casts = [
        'dpc' => 'date',
        'price_per_day' => 'decimal:2'
    ];

    /**
     * Get the contract that owns the car.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    /**
     * Get the model that owns the car.
     */
    public function modele(): BelongsTo
    {
        return $this->belongsTo(Modele::class, 'modele_id');
    }

    /**
     * Get all reservations for this car.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the car's full name (brand + model).
     */
    public function getFullNameAttribute(): string
    {
        return ($this->modele->marque->name ?? 'Unknown') . ' ' . ($this->modele->name ?? 'Unknown');
    }

    /**
     * Get the car's age in years.
     */
    public function getAgeAttribute(): int
    {
        return $this->dpc ? Carbon::parse($this->dpc)->diffInYears(Carbon::now()) : 0;
    }

    /**
     * Get the default price per day if not set.
     */
    public function getPricePerDayAttribute($value): float
    {
        return $value ?? 100.00; // Default price
    }

    /**
     * Check if car is available for given date range.
     */
    public function isAvailableForDates(string $startDate, string $endDate): bool
    {
        return !$this->reservations()
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<', $endDate)
                      ->where('end_date', '>', $startDate);
            })->exists();
    }

    /**
     * Calculate rental price for given date range.
     */
    public function calculatePrice(string $startDate, string $endDate): float
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $start->diffInDays($end);
        
        return $days * $this->price_per_day;
    }

    /**
     * Scope for available cars.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for cars available in date range.
     */
    public function scopeAvailableForDates($query, string $startDate, string $endDate)
    {
        return $query->whereDoesntHave('reservations', function ($q) use ($startDate, $endDate) {
            $q->whereIn('status', ['pending', 'confirmed', 'active'])
              ->where(function ($subQ) use ($startDate, $endDate) {
                  $subQ->where('start_date', '<', $endDate)
                       ->where('end_date', '>', $startDate);
              });
        });
    }
}
