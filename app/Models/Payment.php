<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        return $this->belongsTo(Booking::class, 'id_pemesanan', 'id');
    }

    /**
     * Ambil user yang melakukan pembayaran ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    /**
     * Ambil URL lengkap untuk bukti pembayaran.
     */
    public function getProofImageUrlAttribute()
    {
        $value = $this->attributes['url_bukti_gambar'] ?? null;
        
        if (!$value) {
            return null;
        }

        // Jika sudah berupa URL lengkap, kembalikan seperti semula
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Cek apakah file ada di storage
        if (Storage::disk('public')->exists('payment-proofs/' . $value)) {
            return Storage::disk('public')->url('payment-proofs/' . $value);
        }

        return null;
    }
}

