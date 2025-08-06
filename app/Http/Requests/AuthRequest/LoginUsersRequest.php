<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\Exceptions\NumberParseException;
use Propaganistas\LaravelPhone\PhoneNumber;
use Throwable;

class LoginUsersRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        if (is_numeric($this->username)) {
        
            $this->merge([
                'phone' => $this->username,
                'isEmail'=>false
            ]);
        
        } else if(filter_var($this->username)) {
          
            $this->merge([
                'email' => $this->username,
                'isEmail'=>true
            ]);
        }
    }
    protected function passedValidation(){

        if($this->isEmail == true) 
        {
            return $this->validate([
                'email' => 'required|email|string|exists:users,email',       
            ],
            [
                'email.required'     =>'email wajib diisi',
                'email.email'        => 'format email tidak sesuai',
                'email.exists'       => 'email / password tidak sesuai', // artinya data tidak di temukan
            ]);
        }
        else if($this->isEmail == false) {
           
            try {

                $phoneReformat =  PhoneNumber::make($this->phone,$this->country_phone)->formatE164();
              
              } catch (\Exception $e) {
              
                $phoneReformat = $e;
              }
            
           $this->merge([
            'phone'              =>$phoneReformat, 
            'country_phone'      =>$this->country_phone
            ]);
            
             return  $this->validate([
                'country_phone'          => 'required_with:phone|in:'.config('formValidation.PHONE_COUNTRY_VALIDATION').'',
                'phone'                  =>  'required|phone:country_phone|numeric|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|exists:users,phone',  
            ],[
                'country_phone.required_with'=>'country name wajib diisi apabila menggunakan telp',
                'phone.phone'        => 'format telfon tidak sesuai dengan aturan country phone',
                'phone.required'     => 'format telfon wajib diisi',
                'phone.exists'       => 'phone / password tidak sesuai', // artinya data tidak di temukan
            ]);
        }
       
    }
    public function rules()
    {
        return  [
            'username'       => 'required',
            'password'       => 'required',
            'country_phone'  => 'nullable|in:'.config('formValidation.PHONE_COUNTRY_VALIDATION').''
        ];
    }
    public function messages()
    {
        return [
            'username.required'        => 'Email / phone tidak dapat kosong',
            'username.email'           => 'Format Email tidak sesuai',
            'password.required'        => 'Password tidak dapat kosong',
            'password.required'        => 'Password tidak dapat kosong',
        ];
    }
}
