<?php

namespace App\Http\Requests\Master\Pegawai;

use App\Models\Pegawai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatusRequest extends FormRequest
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
            'id' => 'required|string|uuid|' . Rule::exists(Pegawai::class, 'id'),
            'status' => 'required|boolean'
        ];
    }
}
