<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DaftarValidasi extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            "password" => "required|confirmed|min:6",
            "password_confirmation" => "required"
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
            'email.email' => 'Format email tidak valid',
            'email.required' => 'Email tidak boleh kosong',
            'email.unique' => 'Email sudah terpakai',
            'password.required' => 'Password tidak boleh kosong',
            'password.confirmed' => 'Password tidak sama dengan konfirmasi password',
            'password.min' => 'Password tidak boleh kurang dari 6 karakter',
            'password_confirmation.required' => 'Konfirmasi password tidak boleh kosong'
        ];
    }
}
