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
        Schema::create('pelayanan_perubahan_alamats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tahun_permohonan')->nullable();
            $table->string('bundel_permohonan')->nullable();
            $table->string('no_urut_permohonan')->nullable();
            $table->uuid('pemohon_id')->nullable()->comment('id user pemohon');
            $table->uuid('validasi_id')->nullable()->comment('id user validasi');
            $table->date('tanggal_permohonan')->nullable();
            $table->date('tanggal_validasi')->nullable();
            $table->string('status_pelayanan')->nullable();
            $table->string('kd_propinsi')->nullable();
            $table->string('kd_dati2')->nullable();
            $table->string('kd_kecamatan')->nullable();
            $table->string('kd_kelurahan')->nullable();
            $table->string('kd_blok')->nullable();
            $table->string('no_urut')->nullable();
            $table->string('kd_jns_op')->nullable();
            $table->string('nama_wajib_pajak')->nullable();
            $table->string('jalan_op_lama')->nullable();
            $table->string('blok_kav_no_op_lama')->nullable();
            $table->string('rw_op_lama')->nullable();
            $table->string('rt_op_lama')->nullable();
            $table->string('jalan_op_baru')->nullable();
            $table->string('blok_kav_no_op_baru')->nullable();
            $table->string('rw_op_baru')->nullable();
            $table->string('rt_op_baru')->nullable();
            $table->uuid('created_by')->nullable()->comment('id user create');
            $table->uuid('updated_by')->nullable()->comment('id user update');
            $table->uuid('deleted_by')->nullable()->comment('id user delete');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan_perubahan_alamats');
    }
};
