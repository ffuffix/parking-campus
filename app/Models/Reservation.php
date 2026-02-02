<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'parking_spot_id',
        'start_time',
        'end_time',
        'status',
        'checked_in_at',
        'checked_out_at',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }

    public function can_be_cancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function cancel(): bool
    {
        if (!$this->can_be_cancelled()) {
            return false;
        }

        $this->status = 'cancelled';
        return $this->save();
    }

    public function check_in(): bool
    {
        if ($this->status !== 'confirmed') {
            return false;
        }

        $this->status = 'checked_in';
        $this->checked_in_at = now();
        return $this->save();
    }

    public function check_out(): bool
    {
        if ($this->status !== 'checked_in') {
            return false;
        }

        $this->status = 'checked_out';
        $this->checked_out_at = now();
        return $this->save();
    }

    public function confirm(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        $this->status = 'confirmed';
        return $this->save();
    }
    
    // Scope for active reservations (not cancelled or checked out).
    public function scope_active($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'checked_out']);
    }

    // Scope for upcoming reservations
    public function scope_upcoming($query)
    {
        return $query->where('start_time', '>', now())
            ->whereNotIn('status', ['cancelled', 'checked_out']);
    }
}

