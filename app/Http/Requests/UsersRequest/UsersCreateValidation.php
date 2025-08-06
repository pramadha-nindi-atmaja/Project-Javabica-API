<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;

class UsersCreateValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // protected function passedValidation(){}

    public function authorize()
    {
        // semua orang bisa melakukan pendaftaran
        return true;
      
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function prepareForValidation()
    {
        
        try {
           
            $reFormatPhone =  PhoneNumber::make($this->phone, $this->country_phone)->formatE164();

        } catch (Throwable $e) {

                return $e;
        }

        $this->merge([
            'phone' => $reFormatPhone,
        ]);
    
    }
    public function rules()
    {
        return [
            'avatar'       =>   'nullable',
            'name'       =>   'required',
            'last_name'  => 'required',
            'email'      =>   'required|email|unique:users,email',
            'dob'        => 'required|date_format:Y-m-d',
            'gender'     => 'required|in:MALE,FEMALE',
            
        
            /*
            phone wajib diisi
            phone unique
            regex hanya bisa angka dan + dari awal
            hanya bisa nomor Indonesia, apabila bisa lebih dari 1 negara maka perlu ada p

            detail dapat liat di link bwah ini
            https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements
            https://github.com/Propaganistas/Laravel-Phone
            
            */
            'phone'                      =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|phone:country_phone|unique:users,phone', 
            'country_phone'              => 'required_with:phone|in:'.config('formValidation.PHONE_COUNTRY_VALIDATION').'',

            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
            //    'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            
            
        ];
    }
    public function messages()
    {
       return [
        'name.required'      => 'nama wajib diisi',

        'email.required'     => 'Email wajib diisi',
        'email.unique'       => 'email sudah terdaftar, silahkan gunakan email lainnya',

        'password.required'  => 'Password Wajib diisi',
        'password.min'       => 'minimum password 8 character',
        'password.string'    => 'password harus mengandung huruf',
        'password.regex'    => 'format password harus minimal 8 karakter, minimal 1 huruf kecil, minimal 1 huruf besar, minimal 1 angka, dan 1 special character',
        

        'phone.phone'        => 'format telfon tidak sesuai dengan aturan country phone',
        'phone.required'     =>  'Nomor Hp Wajib diisi', 
        'phone.regex'        =>  'format nomor hp tidak benar', 
        'phone.unique'       =>  'nomor hp sudah terdaftar', 

        'country_phone.in'   =>  'Kode country phone hanya bisa ID', 

        'last_name.required'   =>  'last name wajib diisi', 
        'dob.required'         =>  'tanggal lahir wajib diisi', 
        'dob.date_format'   =>  'format tanggal lahir tidak sesuai yyyy-mm-dd', 
        'gender.required'   =>  'gender wajib diisi', 
        'gender.in'   =>  'gender hanya dapat FEMALE DAN MALE', 

       

       ]; 

    }

}
