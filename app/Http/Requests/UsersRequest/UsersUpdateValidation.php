<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;

class UsersUpdateValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->hasRole('super_admin')) 
        {
             return true;
        }
 
        if(Auth::user()->can('users_update')) 
        {
             return true;
        }
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
            'phone'          =>  'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|phone:country_phone|unique:users,phone,'.$this->user_id, 
            'country_phone'  => 'required_with:phone|in:'.config('formValidation.PHONE_COUNTRY_VALIDATION').'',
            'email'      =>   'required|email|unique:users,email,'.$this->user_id,
            'dob'        => 'required|date_format:Y-m-d',
            'gender'     => 'required|in:MALE,FEMALE',
            'user_id'    =>   'required|exists:users,id',



           
          
        ];
    }
     public function messages()
    {
       return [
        'name.required'      => 'nama users wajib diisi',
        'email.required'     => 'Email users wajib diisi',
        'email.unique'       => 'email users sudah terdaftar, silahkan gunakan email lainnya',
        'user_id.required'   => 'id users wajib diisi',
        'user_id.exists'      => 'id users tidak ditemukan',

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
