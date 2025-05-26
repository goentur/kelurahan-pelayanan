<?php

namespace App\Http\Requests\Sppt\Pelayanan\PerubahanAlamat;

use Illuminate\Foundation\Http\FormRequest;

class CariObjekPajakRequest extends FormRequest
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
               'kelurahan' => 'required|string',
               'kd_blok' => 'required|numeric|digits:3',
               'no_urut' => 'required|numeric|digits:4',
               'kd_jns_op' => 'required|numeric|digits:1',
          ];
     }
}
