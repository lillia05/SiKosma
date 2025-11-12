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
        'id_pengguna',
        'nama',
        'deskripsi',
        'nomor_telepon',
        'tipe',
        'alamat',
        'kota',
        'tautan_google_maps',
        'status',
        'rating',
        'total_ulasan',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    /**
     * Accessor untuk kompatibilitas
     */
    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nama'] = $value;
    }

    public function getTypeAttribute()
    {
        return $this->attributes['tipe'] ?? null;
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['tipe'] = $value;
    }

    public function getAddressAttribute()
    {
        return $this->attributes['alamat'] ?? null;
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['alamat'] = $value;
    }

    public function getCityAttribute()
    {
        return $this->attributes['kota'] ?? null;
    }

    public function setCityAttribute($value)
    {
        $this->attributes['kota'] = $value;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->attributes['total_ulasan'] ?? 0;
    }

    public function setTotalReviewsAttribute($value)
    {
        $this->attributes['total_ulasan'] = $value;
    }

    /**
     * Ambil user yang memiliki kos ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    /**
     * Ambil kamar untuk kos ini.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'id_kos');
    }

    /**
     * Ambil gambar untuk kos ini.
     */
    public function images()
    {
        return $this->hasMany(KosImage::class, 'id_kos');
    }

    /**
     * Ambil pemesanan untuk kos ini.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_kos');
    }
}

