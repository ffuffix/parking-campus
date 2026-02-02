<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'spot_number',
        'type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function is_available(\DateTime $startTime, \DateTime $endTime): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return !$this->reservations()
            ->whereNotIn('status', ['cancelled', 'checked_out'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();
    }

    public function is_occupied(): bool
    {
        return $this->reservations()
            ->where('status', 'checked_in')
            ->exists();
    }

    public function current_reservation()
    {
        return $this->reservations()
            ->whereIn('status', ['checked_in', 'confirmed'])
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();
    }

    public function get_spot_identifier(): string
    {
        return $this->zone ? "{$this->zone->name} - {$this->spot_number}" : $this->spot_number;
    }
}
