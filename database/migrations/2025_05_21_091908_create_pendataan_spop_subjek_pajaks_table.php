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
        Schema::create('pendataan_spop_subjek_pajaks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('pendataan_spop_id')->nullable()->comment('id pendataan spop');
            $table->bigInteger('ref_status_subjek_pajak_id')->nullable()->comment('id status subjek pajak');
            $table->bigInteger('ref_pekerjaan_subjek_pajak_id')->nullable()->comment('id pekerjaan subjek pajak');
            $table->string('nik')->nullable();
            $table->string('npwp')->nullable();
            $table->string('nama')->nullable();
            $table->string('jalan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('blok_kav_no')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendataan_spop_subjek_pajaks');
    }
};
