<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'payment_method',
        'sender_bank_name',
        'sender_account_number',
        'sender_name',
        'proof_image_url',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Ambil pemesanan untuk pembayaran ini.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Ambil user yang melakukan pembayaran ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

