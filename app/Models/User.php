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
        'nama',
        'email',
        'kata_sandi',
        'peran',
        'telepon',
        'alamat',
        'kota',
        'jenis_kelamin',
        'nama_bank',
        'nomor_rekening',
        'status',
        'foto_profil',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'kata_sandi' => 'hashed',
    ];

    /**
     * Accessor untuk kompatibilitas dengan Laravel Auth
     */
    public function getEmailAttribute()
    {
        return $this->attributes['email'] ?? null;
    }

    public function getPasswordAttribute()
    {
        return $this->attributes['kata_sandi'] ?? null;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['kata_sandi'] = $value;
    }

    public function getNameAttribute()
    {
        return $this->attributes['nama'] ?? null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nama'] = $value;
    }

    public function getRoleAttribute()
    {
        return $this->attributes['peran'] ?? null;
    }

    public function setRoleAttribute($value)
    {
        $this->attributes['peran'] = $value;
    }

    public function getPhoneAttribute()
    {
        return $this->attributes['telepon'] ?? null;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['telepon'] = $value;
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

    public function getBankNameAttribute()
    {
        return $this->attributes['nama_bank'] ?? null;
    }

    public function setBankNameAttribute($value)
    {
        $this->attributes['nama_bank'] = $value;
    }

    public function getAccountNumberAttribute()
    {
        return $this->attributes['nomor_rekening'] ?? null;
    }

    public function setAccountNumberAttribute($value)
    {
        $this->attributes['nomor_rekening'] = $value;
    }

    public function getProfilePhotoAttribute()
    {
        return $this->attributes['foto_profil'] ?? null;
    }

    public function setProfilePhotoAttribute($value)
    {
        $this->attributes['foto_profil'] = $value;
    }

    /**
     * Ambil URL foto profil
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->foto_profil) {
            return null;
        }

        // Jika sudah berupa URL lengkap, kembalikan seperti semula
        if (filter_var($this->foto_profil, FILTER_VALIDATE_URL)) {
            return $this->foto_profil;
        }

        // Cek apakah file ada di storage
        if (Storage::disk('public')->exists('profile-photos/' . $this->foto_profil)) {
            return Storage::disk('public')->url('profile-photos/' . $this->foto_profil);
        }

        return null;
    }

    /**
     * Ambil kos yang dimiliki oleh user ini.
     */
    public function kos()
    {
        return $this->hasMany(Kos::class, 'id_pengguna');
    }

    /**
     * Ambil pemesanan yang dibuat oleh user ini.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'id_pengguna');
    }

    /**
     * Ambil pembayaran yang dibuat oleh user ini.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_pengguna');
    }

}
