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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('satuan_kerja_id')->nullable()->comment('id satuan kerja');
            $table->uuid('jabatan_id')->nullable()->comment('id jabatan');
            $table->string('nik')->nullable();
            $table->string('nip')->nullable();
            $table->string('nama')->nullable();
            $table->bigInteger('no_rekening')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
