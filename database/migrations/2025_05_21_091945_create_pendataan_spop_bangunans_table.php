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
        Schema::create('pendataan_spop_bangunans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pendataan_spop_id')->nullable()->comment('id pendataan spop');
            $table->bigInteger('ref_jenis_bangunan_id')->nullable()->comment('id jenis bangunan');
            $table->integer('luas_bangunan')->nullable();
            $table->tinyInteger('jumlah_lantai')->nullable();
            $table->string('tahun_dibangun')->nullable();
            $table->string('tahun_renovasi')->nullable();
            $table->integer('daya_listrik')->nullable();
            $table->bigInteger('ref_kondisi_id')->nullable()->comment('id kondisi');
            $table->bigInteger('ref_konstruksi_id')->nullable()->comment('id konstruksi');
            $table->bigInteger('ref_atap_id')->nullable()->comment('id atap');
            $table->bigInteger('ref_dinding_id')->nullable()->comment('id dinding');
            $table->bigInteger('ref_lantai_id')->nullable()->comment('id lantai');
            $table->bigInteger('ref_langit_id')->nullable()->comment('id langit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendataan_spop_bangunans');
    }
};
