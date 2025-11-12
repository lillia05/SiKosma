<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class KosImage extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'foto_kos';

    protected $fillable = [
        'id_kos',
        'url_gambar',
        'tipe_gambar',
    ];

    /**
     * Ambil kos yang memiliki gambar ini.
     */
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    /**
     * Accessor untuk kompatibilitas
     */
    public function getImageTypeAttribute()
    {
        return $this->attributes['tipe_gambar'] ?? 'general';
    }

    public function setImageTypeAttribute($value)
    {
        $this->attributes['tipe_gambar'] = $value;
    }

    /**
     * Ambil URL lengkap untuk gambar.
     */
    public function getUrlAttribute()
    {
        $value = $this->attributes['url_gambar'];
        
        // Jika sudah berupa URL lengkap, kembalikan seperti semula
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Cek apakah file ada di storage
        if (Storage::disk('public')->exists('kos-images/' . $value)) {
            return Storage::disk('public')->url('kos-images/' . $value);
        }

        // Kembalikan placeholder jika gambar tidak ada
        return 'https://via.placeholder.com/400x300?text=' . urlencode($this->kos->nama ?? 'Kos Image');
    }

    /**
     * Ambil path gambar untuk storage.
     */
    public function getPathAttribute()
    {
        return 'kos-images/' . $this->attributes['url_gambar'];
    }
}

