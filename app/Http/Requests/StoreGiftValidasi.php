<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreGiftValidasi extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required',Rule::unique('gift', 'name')->ignore($this->id)->whereNull('deleted_at')],
            "picture" => "required|mimes:png,jpg,jpeg|max:2048",
            "price" => "required|integer",
            "description" => "required",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama tidak boleh kosong',
            'name.unique' => 'Nama sudah dipakai',
            'picture.required' => 'Gambar tidak boleh kosong',
            'picture.mimes' => 'Ekstensi gambar tidak valid (PNG, JPG, JPEG)',
            'picture.max' => 'Ukuran gambar tidak boleh melebih 2MB',
            'price.required' => 'Harga tidak boleh kosong',
            'price.integer' => 'Harga harus berupa angka',
            'description.required' => 'Deskripsi tidak boleh kosong'
        ];
    }
}
