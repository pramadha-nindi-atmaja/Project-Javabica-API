<?php

namespace App\Http\Requests\ProductPriceRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductPriceDestroyRequest extends FormRequest
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

        if (Auth::user()->can('product_price_destroy')) {
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
            'by_id'                 =>   'required|exists:product_prices,id'
        ];
    }
    public function messages()
    {
       return [

        'by_id.required'               => 'variant price id perlu diisi',
        'by_id.exists'                 => 'variant price id tidak tersedia',

       ]; 
    }
}
