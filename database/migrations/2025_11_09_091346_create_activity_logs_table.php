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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_pengguna')->nullable();
            $table->string('aksi');
            $table->string('tipe_model');
            $table->uuid('id_model');
            $table->text('deskripsi')->nullable();
            $table->json('perubahan')->nullable();
            $table->string('alamat_ip')->nullable();
            $table->timestamps();
            
            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('set null');
            $table->index('id_pengguna');
            $table->index(['tipe_model', 'id_model']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
