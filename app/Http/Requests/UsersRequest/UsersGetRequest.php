<?php

namespace App\Http\Requests\UsersRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UsersGetRequest extends FormRequest
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

       if(Auth::user()->can('users_show')) 
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
            'keyword'         =>   [
                'nullable',
             ],
            'sort_by'          =>   [
                'nullable',
                'in:name,email'
             ],
            'sort_type'       =>   [
                'nullable',
                'in:asc,desc',
                'required_with:sort_by'
             ],
            'paginate'       =>   [
                'nullable',
                'boolean',
                'required_with:page,per_page',
             ],
            'per_page'        =>   [
                'nullable',
                'integer',
                'required_with:paginate',
                'required_if:paginate,1,true'
             ],
            'page'           =>   [
                'nullable',
                'integer',
                'required_with:paginate,per_page',
                'required_if:paginate,1,true'
               
             ],
            'by_id'           =>   [
                'nullable',
                'integer',
             ],
             'by_uuid'           =>   [
                'nullable',
             ],
            'by_email'        =>   [
                'nullable',
                'email',
             ],
            'collection_type' =>   [
                'required',
                'in:showAll'
             ],
        ];
    }
}
