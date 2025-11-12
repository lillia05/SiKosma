<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pemesanan';

    protected $fillable = [
        'id_pemesanan',
        'id_pengguna',
        'id_kamar',
        'id_kos',
        'tanggal_mulai',
        'durasi_tahun',
        'tanggal_selesai',
        'total_harga',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Ambil user yang membuat pemesanan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    /**
     * Ambil kamar untuk pemesanan ini.
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'id_kamar');
    }

    /**
     * Ambil kos untuk pemesanan ini.
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    /**
     * Ambil pembayaran untuk pemesanan ini.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'id_pemesanan');
    }
}

