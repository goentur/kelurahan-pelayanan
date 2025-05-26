<?php

namespace App\Http\Requests\Transaksi\LaporanPenyampaian;

use App\Enums\PenyampaianTipe;
use App\Models\JenisLapor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class DataRequest extends FormRequest
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
               'page' => 'required|numeric',
               'perPage' => 'required|numeric|max:100|min:25',
               'id' => ['required', Rule::exists(JenisLapor::class, 'id')],
          ];
     }
}
