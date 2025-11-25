<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ulasan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'ulasan';

    protected $fillable = [
        'id_kos',
        'id_pengguna',
        'id_pemesanan',
        'rating',
        'ulasan',
    ];

    /**
     * Ambil kos untuk ulasan ini.
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    /**
     * Ambil user yang membuat ulasan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    /**
     * Ambil pemesanan untuk ulasan ini.
     */
    public function pemesanan()
    {
        return $this->belongsTo(Booking::class, 'id_pemesanan');
    }
}
