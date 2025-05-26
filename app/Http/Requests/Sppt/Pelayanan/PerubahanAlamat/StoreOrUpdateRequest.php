<?php

namespace App\Http\Requests\Sppt\Pelayanan\PerubahanAlamat;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrUpdateRequest extends FormRequest
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
               'tipe' => 'required|string|in:tambah,ubah',
               'kelurahan' => 'required|string',
               'kd_blok' => 'required|numeric|digits:3',
               'no_urut' => 'required|numeric|digits:4',
               'kd_jns_op' => 'required|numeric|digits:1',
               'nama_wajib_pajak' => 'required|string',
               'jalan_lama' => 'required|string',
               'blok_kav_no_lama' => 'required|string',
               'rw_lama' => 'required|string|digits:2',
               'rt_lama' => 'required|string|digits:3',
               'jalan_baru' => 'required|string',
               'blok_kav_no_baru' => 'required|string',
               'rw_baru' => 'required|string|digits:2',
               'rt_baru' => 'required|string|digits:3',
          ];
     }
}
