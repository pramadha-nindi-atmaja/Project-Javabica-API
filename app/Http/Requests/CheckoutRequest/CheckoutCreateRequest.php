<?php

namespace App\Http\Requests\CheckoutRequest;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'data.shipping.address_id'      =>   'required|numeric', 
            'data.billing.address_id'       =>   'required_if:data.billing.same_as_shipping,0',
            'data.billing.same_as_shipping' =>   'required|boolean', 
            
            'data.courier.agent'            =>   'required|string',
            'data.courier.service'          =>   'required|string', 
            'data.courier.price'            =>   'required|numeric', 
            'data.courier.etd'              =>   'required|string', 

            'data.product.*.variant_id'     =>   'required|numeric', 
            'data.product.*.qty'            =>   'required|numeric', 
            'data.product.*.note'           =>   'nullable', 

        ];
    }
  
}
