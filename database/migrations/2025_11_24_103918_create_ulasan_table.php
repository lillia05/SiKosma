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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_kos');
            $table->uuid('id_pengguna');
            $table->uuid('id_pemesanan');
            $table->integer('rating')->unsigned(); // 1-5
            $table->text('ulasan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_kos')->references('id')->on('kos')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_pemesanan')->references('id')->on('pemesanan')->onDelete('cascade');
            $table->unique(['id_pemesanan']); // satu pemesanan = satu ulasan
            $table->index('id_kos');
            $table->index('id_pengguna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
