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
        Schema::create('pendataan_spops', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable()->comment('id user');
            $table->uuid('uuid')->nullable();
            $table->string('kd_propinsi')->nullable();
            $table->string('kd_dati2')->nullable();
            $table->string('kd_kecamatan')->nullable();
            $table->string('kd_kelurahan')->nullable();
            $table->string('kd_blok')->nullable();
            $table->string('no_urut')->nullable();
            $table->string('kd_jns_op')->nullable();
            $table->string('blok_kav_no')->nullable();
            $table->string('tahun')->nullable();
            $table->string('jalan')->nullable();
            $table->string('rw')->nullable();
            $table->string('rt')->nullable();
            $table->string('koordinat')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendataan_spops');
    }
};
