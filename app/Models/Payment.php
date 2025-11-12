<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pembayaran';

    protected $fillable = [
        'id_pemesanan',
        'id_pengguna',
        'jumlah',
        'metode_pembayaran',
        'nama_bank_pengirim',
        'nomor_rekening_pengirim',
        'nama_pengirim',
        'url_bukti_gambar',
        'status',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    /**
     * Ambil pemesanan untuk pembayaran ini.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_pemesanan');
    }

    /**
     * Ambil user yang melakukan pembayaran ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}

