<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;

class ValidateVerificationAccountRequestUsers extends FormRequest
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
            'otp'      =>   'required|digits:6|numeric',

        ];
    }
    public function messages()
    {
        return [
            'otp.required'        => 'OTP tidak dapat kosong',
            'otp.digits'          => 'OTP harus 6 digit',
            'otp.numeric'         => 'OTP hanya dapat karakter number',
        ];
    }
}
