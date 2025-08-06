<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'current_password'        => 'required',
            
            'password' => [
                'required',
                'string',
                'confirmed',
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
             //   'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            
        ];
    }
    public function messages()
    {
       return [
        'current_password.required'       => 'Password Saat ini Wajib diisi',
        'password.confirmed'              => 'password dan konfirmasi password tidak sama',

        'password.required'               => 'Password Wajib diisi',
        'password.min'                    => 'minimum password 8 character',
        'password.string'                 => 'password harus mengandung huruf',
        'password.regex'                  => 'format password harus minimal 8 karakter, minimal 1 huruf kecil, minimal 1 huruf besar, minimal 1 angka, dan 1 special character',
        
        'password_confirmation.required'  => 'password confirmation Wajib diisi',
        'password_confirmation.min'       => 'minimum password confirmation 8 character',
        'password_confirmation.string'    => 'password confirmation harus mengandung huruf',
        'password_confirmation.regex'     => 'format password confirmation harus minimal 8 karakter, minimal 1 huruf kecil, minimal 1 huruf besar, minimal 1 angka, dan 1 special character',
        
       ]; 

    }
}
