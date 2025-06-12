<?php

namespace App\Http\Requests\Pendataan\Spop;

use App\Models\Pendataan\PendataanSpopBangunan;
use App\Models\Ref\RefAtap;
use App\Models\Ref\RefDinding;
use App\Models\Ref\RefJenisBangunan;
use App\Models\Ref\RefKondisi;
use App\Models\Ref\RefKonstruksi;
use App\Models\Ref\RefLangit;
use App\Models\Ref\RefLantai;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeleteBangunanRequest extends FormRequest
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
            'id' => 'required|' . Rule::exists(PendataanSpopBangunan::class, 'id'),
        ];
    }
}
