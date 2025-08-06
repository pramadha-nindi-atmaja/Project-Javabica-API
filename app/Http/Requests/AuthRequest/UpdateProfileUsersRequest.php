<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileUsersRequest extends FormRequest
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
       
        
        $checkHeaderAuth = Auth::user($this->header('Authorization'));

        return  [
            'avatar'          =>   'nullable',
            'email'           =>   'required|email|unique:users,email,'.$checkHeaderAuth->id,
            'name'            =>   'required',
            'last_name'       => 'required',
            'phone'           =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|phone:country_phone|unique:users,phone,'.$checkHeaderAuth->id, 
            'country_phone'   => 'required_with:phone|in:'.config('formValidation.PHONE_COUNTRY_VALIDATION').'',
            'dob'             => 'required|date_format:Y-m-d',
            'gender'          => 'required|in:MALE,FEMALE',
        ];
    }
    public function messages()
    {
        return [
            'name.required'      => 'nama users wajib diisi',
            'email.required'     => 'Email users wajib diisi',
            'email.unique'       => 'email users sudah terdaftar, silahkan gunakan email lainnya',
          
            'last_name.required'   =>  'last name wajib diisi', 
            'dob.required'         =>  'tanggal lahir wajib diisi', 
            'dob.date_format'      =>  'format tanggal lahir tidak sesuai yyyy-mm-dd', 
            'gender.required'      =>  'gender wajib diisi', 
            'gender.in'            =>  'gender hanya dapat FEMALE DAN MALE', 
    
            'phone.phone'        => 'format telfon tidak sesuai dengan aturan country phone',
            'phone.required'     =>  'Nomor Hp Wajib diisi', 
            'phone.regex'        =>  'format nomor hp tidak benar', 
            'phone.unique'       =>  'nomor hp sudah terdaftar', 
    
            'country_phone.in'   =>  'Kode country phone hanya bisa ID', 
    
           ]; 
    }
}
