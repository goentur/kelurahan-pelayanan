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
        Schema::create('jenis_lapors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama')->nullable();
            $table->tinyInteger('no_urut')->nullable();
            $table->string('jenis')->nullable();
            $table->string('keterangan')->nullable();
            $table->dateTime('tanggal_awal')->nullable();
            $table->dateTime('tanggal_akhir')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_lapors');
    }
};
