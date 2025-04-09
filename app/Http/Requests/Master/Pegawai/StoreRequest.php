<?php

namespace App\Http\Requests\Master\Pegawai;

use App\Models\Jabatan;
use App\Models\SatuanKerja;
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
            'nik' => 'required|numeric|digits:16',
            'nip' => 'nullable|numeric|digits:18',
            'nama' => 'required|string|max:255',
            'no_rekening' => 'required|numeric',
            'jabatan' => 'required|string|uuid|' . Rule::exists(Jabatan::class, 'id'),
            'satuan_kerja' => 'required|string|uuid|' . Rule::exists(SatuanKerja::class, 'id'),
        ];
    }
}
