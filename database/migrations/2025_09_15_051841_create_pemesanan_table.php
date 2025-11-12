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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_pemesanan')->unique();
            $table->uuid('id_pengguna');
            $table->uuid('id_kamar');
            $table->uuid('id_kos');
            $table->date('tanggal_mulai');
            $table->integer('durasi_tahun');
            $table->date('tanggal_selesai');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['PENDING', 'CONFIRMED', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_kamar')->references('id')->on('kamar')->onDelete('cascade');
            $table->foreign('id_kos')->references('id')->on('kos')->onDelete('cascade');
            $table->index('id_pengguna');
            $table->index('id_kamar');
            $table->index('id_kos');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
