<?php

namespace App\Http\Requests\Sppt\Data;

use Illuminate\Foundation\Http\FormRequest;

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
               'kelurahan' => 'nullable|string',
               'kd_blok' => 'nullable|numeric|digits:3',
               'no_urut' => 'nullable|numeric|digits:4',
               'nama_wp' => 'nullable|string',
          ];
     }
}
