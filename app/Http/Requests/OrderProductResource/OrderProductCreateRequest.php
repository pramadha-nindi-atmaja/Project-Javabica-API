<?php

namespace App\Http\Requests\OrderProductResource;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderProductCreateRequest extends FormRequest
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
        
        if(Auth::user()->can('order_product_create')) {
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
            'product_name'             =>   'required',
            'fk_product_id'            =>   'required',
            'fk_variant_id'            =>   'required',
            'image'                    =>   'nullable',
            'sku'                      =>   'required',
            'variant_description'      =>   'nullable',  
            'qty'                      =>   'required|integer',
            'acctual_price'            =>   'required|integer',  
            'discount_price'           =>   'required|integer',
            'purchase_price'           =>   'required|integer',  
            'fk_order_id'              =>   'required',
            'note'                     =>   'nullable'
        ];
    }
}
