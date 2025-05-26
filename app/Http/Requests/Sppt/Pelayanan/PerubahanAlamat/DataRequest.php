<?php

namespace App\Http\Requests\Sppt\Pelayanan\PerubahanAlamat;

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
               'status' => 'nullable|string|in:SEMUA,MENUNGGU,SELESAI,TOLAK',
               'berdasarkan' => 'nullable|string|in:Nomor,NOP,Nama',
               'search' => 'nullable|string|max:255',
          ];
     }
}
