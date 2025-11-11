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
            $table->uuid('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('phone_number');
            $table->enum('type', ['Putra', 'Putri', 'Campur'])->default('Putra');
            $table->string('address');
            $table->string('city');
            $table->string('google_maps_link')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif', 'Menunggu', 'Ditolak', 'Disetujui'])->default('Menunggu');
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
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
