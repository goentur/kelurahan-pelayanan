<?php

namespace App\Http\Requests\Pendataan\Spop;

use App\Models\Pendataan\PendataanSpop;
use App\Models\Ref\RefAtap;
use App\Models\Ref\RefDinding;
use App\Models\Ref\RefJenisBangunan;
use App\Models\Ref\RefKondisi;
use App\Models\Ref\RefKonstruksi;
use App\Models\Ref\RefLangit;
use App\Models\Ref\RefLantai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddBangunanRequest extends FormRequest
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
            'pendataan_spop' => 'required|' . Rule::exists(PendataanSpop::class, 'id'),
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
