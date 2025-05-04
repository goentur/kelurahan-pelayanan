<?php

namespace App\Http\Requests\Transaksi\Penyampaian;

use Illuminate\Foundation\Http\FormRequest;

class TersampaikanRequest extends FormRequest
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
               'id' => 'required|string',
               'value' => 'required|string',
               'nama_wp' => 'required|string',
               'alamat_objek' => 'required|string',
               'nominal' => 'required|string',
          ];
     }
}
