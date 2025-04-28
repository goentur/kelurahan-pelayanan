<?php

namespace App\Http\Requests\Master\SatuanKerja;

use App\Models\SatuanKerja;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'nama' => 'required|string|max:255',
            'kode_ref' => 'required|numeric',
            'atasan_satuan_kerja' => 'nullable|string|uuid|' . Rule::exists(SatuanKerja::class, 'id'),
            'role' => 'required|in:KECAMATAN,KELURAHAN',
        ];
    }
}
