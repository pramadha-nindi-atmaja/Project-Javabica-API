<?php

namespace App\Http\Requests\ShippingAddressRequest;

use App\Models\User_shipping_address;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShippingAddressDestroyRequest extends FormRequest
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

        if (Auth::user()->can('shipping_address_destroy')) {
            return true;
        }

        $getUserShipping =  User_shipping_address::find($this->by_id);

        if($getUserShipping->fk_user_id == Auth::user()->id)
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
            'by_id'     =>'required'
        ];
    }
}
