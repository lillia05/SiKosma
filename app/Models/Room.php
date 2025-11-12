<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'kamar';

    protected $fillable = [
        'id_kos',
        'nomor_kamar',
        'harga_per_tahun',
        'ukuran_kamar',
        'fasilitas',
        'status',
    ];

    protected $casts = [
        'harga_per_tahun' => 'decimal:2',
        'ukuran_kamar' => 'decimal:2',
    ];

    /**
     * Accessor untuk kompatibilitas
     */
    public function getPricePerYearAttribute()
    {
        return $this->attributes['harga_per_tahun'] ?? null;
    }

    public function setPricePerYearAttribute($value)
    {
        $this->attributes['harga_per_tahun'] = $value;
    }

    /**
     * Ambil kos yang memiliki kamar ini.
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    /**
     * Ambil pemesanan untuk kamar ini.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_kamar');
    }
}

