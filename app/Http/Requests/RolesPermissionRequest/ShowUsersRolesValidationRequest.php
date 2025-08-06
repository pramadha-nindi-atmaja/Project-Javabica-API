<?php

namespace App\Http\Requests\RolesPermissionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShowUsersRolesValidationRequest extends FormRequest
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

        if(Auth::user()->can('model_has_role_show')) 
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
            'user_uuid'                    => 'required|string|exists:users,uuid', 
        ];
    }
    public function messages()
    {
        return [
            'user_uuid.required'                   => 'user uuid tidak boleh kosong',
            'user_uuid.exists'                     => 'user uuid tidak ditemukan',
           
           ]; 
    }
}
