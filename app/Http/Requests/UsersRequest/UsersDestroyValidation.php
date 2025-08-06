<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UsersDestroyValidation extends FormRequest
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
 
        if(Auth::user()->can('users_destroy')) 
        {
             return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'       =>   'required|exists:users,id',
            
        ];
    }
    public function messages()
    {
       return [
        'user_id.required'      => 'id wajib diisi',
        'user_id.exists'        => 'id tidak ditemukan',
       ]; 

    }
}
