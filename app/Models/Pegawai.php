<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasUuids;
    use SoftDeletes;
    protected $fillable = ['satuan_kerja_id', 'jabatan_id', 'nik', 'nip', 'nama', 'no_rekening', 'status'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}
