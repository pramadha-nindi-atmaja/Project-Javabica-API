<?php

namespace App\Http\Requests\RolesPermissionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShowPermissionFromRoleValidationRequest extends FormRequest
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
        if(Auth::user()->can('permission_from_role_show')) 
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
            'roles_id'                             => 'required', 
          
             //Paginate
             'paginate'       =>   [
                'nullable',
                'boolean',
                'required_with:page,per_page',
             ],
            'per_page'        =>   [
                'nullable',
                'numeric',
                'required_with:paginate',
               ' required_if:paginate,1,true'
             ],
            'page'           =>   [
                'nullable',
                'numeric',
                'required_with:paginate,per_page',
                'required_if:paginate,1,true'
               
             ],
           
        ];
    }
    public function messages()
    {
        return [
            'id.required'             => 'id role  tidak boleh kosong',
            'id.exists'               => 'is  roles tidak ditemukan',
           ]; 
    }
}
