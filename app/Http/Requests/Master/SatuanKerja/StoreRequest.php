<?php

namespace App\Http\Requests\Master\SatuanKerja;

use App\Models\SatuanKerja;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'nama' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'kode_ref' => 'required|numeric',
            'atasan_satuan_kerja' => 'nullable|string|uuid|' . Rule::exists(SatuanKerja::class, 'id'),
            'role' => 'required|in:KECAMATAN,KELURAHAN',
        ];
    }
}
