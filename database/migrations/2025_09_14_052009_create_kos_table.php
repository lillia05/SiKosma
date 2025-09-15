<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::create('kos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('id_pengguna')->constrained('pengguna')->onDelete('cascade');     
                $table->string('nama_kos');
                $table->integer('jumlah_kamar');
                $table->integer('kamar_tersedia');
                $table->text('deskripsi');
                $table->text('alamat');
                $table->enum('kelurahan', ['Kampung Baru', 'Gedong Meneng']);
                $table->string('kecamatan')->default('Rajabasa');
                $table->string('kota')->default('Bandar Lampung');
                $table->enum('tipe_kos', ['putra', 'putri', 'campur']);
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('alasan_penolakan')->nullable();
                $table->timestamp('tanggal_disetujui')->nullable();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
