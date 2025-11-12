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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_pengguna');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['booking', 'payment', 'verification', 'system'])->default('system');
            $table->string('id_terkait')->nullable();
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();
            
            $table->foreign('id_pengguna')->references('id')->on('users')->onDelete('cascade');
            $table->index('id_pengguna');
            $table->index('sudah_dibaca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
