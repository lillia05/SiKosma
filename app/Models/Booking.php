<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'user_id',
        'room_id',
        'kos_id',
        'start_date',
        'duration_years',
        'end_date',
        'total_price',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    /**
     * Ambil user yang membuat pemesanan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil kamar untuk pemesanan ini.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Ambil kos untuk pemesanan ini.
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class);
    }

    /**
     * Ambil pembayaran untuk pemesanan ini.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

