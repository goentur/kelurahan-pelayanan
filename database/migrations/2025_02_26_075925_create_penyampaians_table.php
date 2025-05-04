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
        Schema::create('penyampaians', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable()->comment('id user');
            $table->uuid('penyampaian_keterangan_id')->nullable()->comment('id penyampaian keterangan');
            $table->string('kd_propinsi')->nullable();
            $table->string('kd_dati2')->nullable();
            $table->string('kd_kecamatan')->nullable();
            $table->string('kd_kelurahan')->nullable();
            $table->string('kd_blok')->nullable();
            $table->string('no_urut')->nullable();
            $table->string('kd_jns_op')->nullable();
            $table->string('tahun')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->string('tipe')->nullable();
            $table->string('status')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyampaians');
    }
};
