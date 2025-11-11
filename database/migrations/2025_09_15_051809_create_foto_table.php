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
        Schema::create('kos_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kos_id');
            $table->string('image_url');
            $table->string('image_type')->default('general');
            $table->timestamps();
            
            $table->foreign('kos_id')->references('id')->on('kos')->onDelete('cascade');
            $table->index('kos_id');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos_images');
    }
};
