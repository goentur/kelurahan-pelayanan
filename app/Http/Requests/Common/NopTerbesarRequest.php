<?php

namespace App\Http\Requests\Common;

use Illuminate\Foundation\Http\FormRequest;

class NopTerbesarRequest extends FormRequest
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
            'kd_propinsi' => 'required|numeric|digits:2',
            'kd_dati2' => 'required|numeric|digits:2',
            'kd_kecamatan' => 'required|numeric|digits:3',
            'kd_kelurahan' => 'required|numeric|digits:3',
            'kd_blok' => 'required|numeric|digits:3',
        ];
    }
}
