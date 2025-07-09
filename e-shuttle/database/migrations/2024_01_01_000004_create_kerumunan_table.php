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
        Schema::create('kerumunan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_halte');
            $table->datetime('waktu');
            $table->integer('jumlah_kerumunan');
            $table->timestamps();
            
            $table->foreign('id_halte')->references('id')->on('halte')->onDelete('cascade');
            $table->index(['id_halte', 'waktu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerumunan');
    }
};