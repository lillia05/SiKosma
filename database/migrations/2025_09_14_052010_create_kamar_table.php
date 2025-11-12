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
        Schema::create('kamar', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_kos');
            $table->string('nomor_kamar');
            $table->decimal('harga_per_tahun', 12, 2);
            $table->decimal('ukuran_kamar', 5, 2);
            $table->text('fasilitas')->nullable();
            $table->enum('status', ['Tersedia', 'Terisi', 'Pemeliharaan'])->default('Tersedia');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('id_kos')->references('id')->on('kos')->onDelete('cascade');
            $table->index('id_kos');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
