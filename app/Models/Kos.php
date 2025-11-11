<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kos extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'phone_number',
        'type',
        'address',
        'city',
        'google_maps_link',
        'status',
        'rating',
        'total_reviews',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    /**
     * Ambil user yang memiliki kos ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil kamar untuk kos ini.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Ambil gambar untuk kos ini.
     */
    public function images()
    {
        return $this->hasMany(KosImage::class);
    }

    /**
     * Ambil pemesanan untuk kos ini.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

