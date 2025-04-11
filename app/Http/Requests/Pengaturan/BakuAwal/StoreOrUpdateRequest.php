<?php

namespace App\Http\Requests\Pengaturan\BakuAwal;

use App\Enums\JabatanJenis;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOrUpdateRequest extends FormRequest
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
            'tahun_pajak' => 'required|numeric|digits:4|max:' . now()->year,
            'kd_propinsi' => 'required|numeric|digits:2',
            'kd_dati2' => 'required|numeric|digits:2',
            'kd_kecamatan' => 'nullable|numeric|digits:3',
            'kd_kelurahan' => 'nullable|numeric|digits:3',
            'kd_blok' => 'nullable|numeric|digits:3',
            'no_urut' => 'nullable|numeric|digits:4',
        ];
    }
}
