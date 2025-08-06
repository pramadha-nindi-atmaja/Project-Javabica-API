<?php

namespace App\Http\Requests\RolesPermissionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateRolesRequestValidation extends FormRequest
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
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            'roles_name'    => 'required|unique:roles,name', 
        ];
    }
    public function messages()
    {
        return [
            'roles_name.required'    => 'roles name wajib diisi',
            'roles_name.unique'      => 'roles name sudah tersedia, silahkan pilih nama roles lainnya',
           
           
           ]; 
    }
}
