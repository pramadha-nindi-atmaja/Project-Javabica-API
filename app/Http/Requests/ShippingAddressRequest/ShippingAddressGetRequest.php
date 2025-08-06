<?php

namespace App\Http\Requests\ShippingAddressRequest;

use App\Models\User_shipping_address;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShippingAddressGetRequest extends FormRequest
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

        if (Auth::user()->can('shipping_address_show')) 
        {
            return true;
        }

        //checking shiiping id request from user
        // if($this->by_user == Auth::user()->id )
        // {
        //     return true;
        // }
        return true;
        
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
                'in:first_name,last_name,address'
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
                'numeric',
                'required_with:paginate',
                ' required_if:paginate,1,true'
             ],
            'page'           =>   [
                'nullable',
                'numeric',
                'required_with:paginate,per_page',
                ' required_if:paginate,1,true'
               
             ],
            'by_id'           =>   [
                'nullable',
                'numeric',
               
             ],
             'by_user'           =>   [
                'nullable', 
             ],
           
        ];
    }
}
