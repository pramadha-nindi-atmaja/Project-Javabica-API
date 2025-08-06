<?php

namespace App\Http\Requests\RolesPermissionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RemovePermissionUserValidationRequest extends FormRequest
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

        if(Auth::user()->can('model_has_permission_destroy')) 
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
            'user_uuid'              => 'required|string|exists:users,uuid',
            'permission_name'        => 'required|string|exists:permissions,name',
          
        ];
    }
    public function messages()
    {
        return [
            'user_uuid.required'       => 'user uuid user tidak boleh kosong',
            'user_uuid.string'       => 'user tidak ditemukan',
            'permission_name.required' => 'permission name wajib diisi',
            'permission_name.string'   => 'gunakan nama permission untuk menambah/ menghapus permission',
            'permission_name.exists' => 'permission name tidak di temukan',
           ]; 
    }
}
