<?php

namespace App\Http\Requests\ShippingAddressRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ShippingAddressUpdateRequest extends FormRequest
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

        if (Auth::user()->can('shipping_address_update')) {
            return true;
        }


        $this->merge([
            'fk_user_id' =>Auth::user()->id,
        ]);

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
            'first_name'             => 'required',
            'last_name'              => 'required',
            'phone_number'           => 'required|numeric',
            'label_place'            => 'required',
            'courier_note'           => 'required',
            'address'                => 'required',
            'city'                   => 'required',
            'fk_user_id'             => 'required',
            'postal_code'            => 'required',
            'id'                     => 'required',
           
        ];
    }
}
