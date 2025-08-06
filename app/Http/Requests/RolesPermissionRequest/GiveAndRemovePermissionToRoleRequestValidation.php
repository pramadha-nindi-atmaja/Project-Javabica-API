<?php

namespace App\Http\Requests\RolesPermissionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GiveAndRemovePermissionToRoleRequestValidation extends FormRequest
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

        if(Auth::user()->can('permission_to_role_create_delete')) 
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
            'roles_name'                             => 'required|exists:roles,name', 
            'permission_name'                        => 'required',
            'is_create_permission_to_roles'              => 'required|boolean', 
        ];
    }
    public function messages()
    {
        return [
            'roles_name.required'                                    => 'role name tidak boleh kosong',
            'roles_name.exists'                                      => 'role name tidak terdaftar',
            'is_create_permission_to_roles.required'                     => 'is create permission to roles harus diisi',
            'is_create_permission_to_roles.in'                           => 'hanya dapat true or false',
             
           ]; 
    }
}
