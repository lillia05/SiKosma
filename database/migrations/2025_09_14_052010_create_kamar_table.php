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
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kos_id');
            $table->string('room_number');
            $table->decimal('price_per_year', 12, 2);
            $table->decimal('room_size', 5, 2);
            $table->text('facilities')->nullable();
            $table->enum('status', ['Tersedia', 'Terisi', 'Pemeliharaan'])->default('Tersedia');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('kos_id')->references('id')->on('kos')->onDelete('cascade');
            $table->index('kos_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
