<?php

namespace App\Http\Requests\AuthRequest;
use Illuminate\Foundation\Http\FormRequest;

class ResetNewPasswordRequest extends FormRequest
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
        return  [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
             //   'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
              //  'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ];
    }
    public function messages()
    {
        return [
            'token.required'        => 'Token tidak dikenali',
            'email.email'           => 'Email format tidak benar',
            'email.required'        => 'Email tidak boleh kosong',
            'password.required'  => 'Password Wajib diisi',
            'password.min'       => 'minimum password 8 character',
            'password.string'    => 'password harus mengandung huruf',
            'password.regex'    => 'format password harus minimal 8 karakter, minimal 1 huruf kecil, minimal 1 huruf besar, minimal 1 angka, dan 1 special character',
            
           ];
    }
}
