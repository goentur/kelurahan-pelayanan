<?php

namespace App\Http\Requests\Master\JenisLapor;

use App\Enums\PenyampaianTipe;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'no_urut' => 'required|numeric',
            'jenis' => ['required', new Enum(PenyampaianTipe::class)],
            'keterangan' => 'required|string|max:255',
            'tanggal_awal' => 'required|string',
            'tanggal_akhir' => 'required|string',
        ];
    }
}
