<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'kos_id',
        'room_number',
        'price_per_year',
        'room_size',
        'facilities',
        'status',
    ];

    protected $casts = [
        'price_per_year' => 'decimal:2',
        'room_size' => 'decimal:2',
    ];

    /**
     * Ambil kos yang memiliki kamar ini.
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    /**
     * Ambil pemesanan untuk kamar ini.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

