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
        Schema::create('pendataan_spop_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pendataan_spop_id')->nullable()->comment('id pendataan spop');
            $table->tinyInteger('jumlah_ac')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendataan_spop_fasilitas');
    }
};
