<?php

namespace App\Http\Requests\OrderProductResource;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderProductDestroyRequest extends FormRequest
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
        
        if(Auth::user()->can('order_product_destroy')) {
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
            'by_id'                 =>   'required|exists:order_products,id'
        ];
    }
    public function messages()
    {
       return [

        'by_id.required'               => 'order product id perlu diisi',
        'by_id.exists'                 => 'order product id tidak tersedia',

       ]; 
    }
}
