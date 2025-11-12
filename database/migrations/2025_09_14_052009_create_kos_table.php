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
            $table->uuid('id')->primary();
            $table->uuid('id_pengguna');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('nomor_telepon');
            $table->enum('tipe', ['Putra', 'Putri', 'Campur'])->default('Putra');
            $table->string('alamat');
            $table->string('kota');
            $table->string('tautan_google_maps')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Menunggu', 'Ditolak', 'Disetujui'])->default('Menunggu');
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('total_ulasan')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
            $table->index('id_pengguna');
            $table->index('status');
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
