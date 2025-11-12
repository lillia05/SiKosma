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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_pemesanan');
            $table->uuid('id_pengguna');
            $table->decimal('jumlah', 12, 2);
            $table->enum('metode_pembayaran', ['Transfer Bank', 'E-Wallet'])->default('Transfer Bank');
            $table->string('nama_bank_pengirim');
            $table->string('nomor_rekening_pengirim');
            $table->string('nama_pengirim');
            $table->string('url_bukti_gambar')->nullable();
            $table->enum('status', ['Pending', 'Verified', 'Rejected'])->default('Pending');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_pemesanan')->references('id')->on('pemesanan')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
            $table->index('id_pemesanan');
            $table->index('id_pengguna');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
