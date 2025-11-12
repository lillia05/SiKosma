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
        Schema::create('foto_kos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_kos');
            $table->string('url_gambar');
            $table->string('tipe_gambar')->default('general');
            $table->timestamps();
            
            $table->foreign('id_kos')->references('id')->on('kos')->onDelete('cascade');
            $table->index('id_kos');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_kos');
    }
};
