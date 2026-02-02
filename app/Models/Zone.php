<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'max_spots',
    ];

    protected $casts = [
        'max_spots' => 'integer',
    ];

    public function parkingspots()
    {
        return $this->hasMany(ParkingSpot::class);
    }

    // get the number of active spots in this zone
    public function get_active_spots_count_attribute(): int
    {
        return $this->parkingspots()->where('is_active', true)->count();
    }

    // get the number of occupied spots in this zone
    public function get_occupied_spots_count_attribute(): int
    {
        return $this->parkingspots()
            ->whereHas('reservations', function ($query) {
                $query->where('status', 'checked_in')
                    ->orWhere(function ($q) {
                        $q->whereIn('status', ['pending', 'confirmed'])
                            ->where('start_time', '<=', now())
                            ->where('end_time', '>=', now());
                    });
            })
            ->count();
    }

    // get the number of available spots in this zone
    public function get_available_spots_count_attribute(): int
    {
        return $this->active_spots_count - $this->occupied_spots_count;
    }

    // returns the occupancy percentage of the zone
    public function get_occupancy_percentage_attribute(): float
    {
        if ($this->active_spots_count === 0) {
            return 0;
        }

        return round(($this->occupied_spots_count / $this->active_spots_count) * 100, 1);
    }
}
