<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'gender',
        'bank_name',
        'account_number',
        'status',
        'profile_photo',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Ambil kos yang dimiliki oleh user ini.
     */
    public function kos()
    {
        return $this->hasMany(Kos::class);
    }

    /**
     * Ambil pemesanan yang dibuat oleh user ini.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Ambil pembayaran yang dibuat oleh user ini.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}
