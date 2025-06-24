<?php

namespace App\Http\Requests\Pendataan\Spop;

use App\Models\Ref\RefAtap;
use App\Models\Ref\RefDinding;
use App\Models\Ref\RefJenisBangunan;
use App\Models\Ref\RefJenisPendataanSpop;
use App\Models\Ref\RefJenisTanah;
use App\Models\Ref\RefKondisi;
use App\Models\Ref\RefKonstruksi;
use App\Models\Ref\RefLangit;
use App\Models\Ref\RefLantai;
use App\Models\Ref\RefPekerjaanSubjekPajak;
use App\Models\Ref\RefStatusSubjekPajak;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis' => 'required|' . Rule::exists(RefJenisPendataanSpop::class, 'id'),
            'kd_propinsi' => 'required|numeric|digits:2',
            'kd_dati2' => 'required|numeric|digits:2',
            'kd_kecamatan' => 'required|numeric|digits:3',
            'kd_kelurahan' => 'required|numeric|digits:3',
            'kd_blok' => 'required|numeric|digits:3',
            'no_urut' => 'required|numeric|digits:4',
            'kd_jns_op' => 'required|numeric|digits:1',
            'jalan' => 'required|string|max:255',
            'blok_kav_no' => 'nullable|string|max:255',
            'rw' => 'required|numeric|digits:2',
            'rt' => 'required|numeric|digits:3',
            'luas_tanah' => 'required|numeric',
            'no_sertipikat' => 'nullable|string',
            'tanah' => 'required|' . Rule::exists(RefJenisTanah::class, 'id'),
            'keterangan' => 'required|string',
            'koordinat' => 'required|array',
            'status' => 'required|' . Rule::exists(RefStatusSubjekPajak::class, 'id'),
            'pekerjaan' => 'required|' . Rule::exists(RefPekerjaanSubjekPajak::class, 'id'),
            'nik' => 'required|numeric|digits:16',
            'npwp' => 'nullable|numeric|digits:16',
            'nama' => 'required|string|max:255',
            'jalan_sp' => 'required|string|max:255',
            'blok_kav_no_sp' => 'nullable|string|max:255',
            'rw_sp' => 'required|numeric|digits:2',
            'rt_sp' => 'required|numeric|digits:3',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'kode_pos' => 'nullable|numeric|digits:5',
            'no_telp' => 'nullable|numeric',
            'email' => 'nullable|email',
            'jenis_bangunan' => 'required|' . Rule::exists(RefJenisBangunan::class, 'id'),
            'luas_bangunan' => 'required|numeric',
            'jumlah_lantai' => 'required|numeric',
            'tahun_dibangun' => 'required|numeric|digits:4',
            'tahun_renovasi' => 'required|numeric|digits:4',
            'daya_listrik' => 'required|numeric',
            'jumlah_ac' => 'required|numeric',
            'kondisi' => 'required|' . Rule::exists(RefKondisi::class, 'id'),
            'konstruksi' => 'required|' . Rule::exists(RefKonstruksi::class, 'id'),
            'atap' => 'required|' . Rule::exists(RefAtap::class, 'id'),
            'dinding' => 'required|' . Rule::exists(RefDinding::class, 'id'),
            'lantai' => 'required|' . Rule::exists(RefLantai::class, 'id'),
            'langit' => 'required|' . Rule::exists(RefLangit::class, 'id'),
        ];
    }
}
