<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'notifikasi';

    protected $fillable = [
        'id_pengguna',
        'judul',
        'pesan',
        'tipe',
        'id_terkait',
        'sudah_dibaca',
        'dibaca_pada',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'dibaca_pada' => 'datetime',
    ];

    /**
     * Ambil user yang memiliki notifikasi ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    /**
     * Mark notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        $this->update([
            'sudah_dibaca' => true,
            'dibaca_pada' => now(),
        ]);
    }
}

