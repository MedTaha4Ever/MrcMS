<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'f_name',
        'l_name',
        'email',
        'phone',
        'cin',
        'adrs',
        'b_date',
        'permis',
        'date_permis',
        'notes',
        'contract_id',
        'status'
    ];

    protected $casts = [
        'b_date' => 'date',
        'date_permis' => 'date',
    ];

    /**
     * Get all reservations for this client.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the client's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->f_name . ' ' . $this->l_name;
    }

    /**
     * Get the client's age.
     */
    public function getAgeAttribute(): int
    {
        return $this->b_date ? Carbon::parse($this->b_date)->age : 0;
    }

    /**
     * Get the license age in years.
     */
    public function getLicenseAgeAttribute(): int
    {
        return $this->date_permis ? Carbon::parse($this->date_permis)->diffInYears(Carbon::now()) : 0;
    }

    /**
     * Check if client has an active contract.
     */
    public function hasActiveContract(): bool
    {
        return $this->contract_id > 0;
    }

    /**
     * Scope for active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
