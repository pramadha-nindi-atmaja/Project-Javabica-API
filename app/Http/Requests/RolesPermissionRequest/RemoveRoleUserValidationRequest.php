<?php

namespace App\Http\Requests\RolesPermissionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RemoveRoleUserValidationRequest extends FormRequest
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

        if(Auth::user()->can('model_has_role_destroy')) 
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
            'user_uuid'        => 'required|exists:users,uuid',
            'role_name'        => 'required|string|exists:roles,name',
        
        ];
    }
    public function messages()
    {
        return [
            'user_uuid.required' => 'user uuid  tidak boleh kosong',
            'user_uuid.exists'   => 'user uuid  tidak ditemukan',
            'role_name.required' => 'role name wajib diisi',
            'role_name.string'   => 'gunakan nama role untuk menambah/menghapus role',
            'role_name.exists'   => 'roles name tidak di temukan',
           ]; 
    }
}
