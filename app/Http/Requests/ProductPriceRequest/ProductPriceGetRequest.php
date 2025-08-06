<?php

namespace App\Http\Requests\ProductPriceRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductPriceGetRequest extends FormRequest
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

        if (Auth::user()->can('product_price_show')) {
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
                'in:price,discount,start_qty'
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
                'required_if:paginate,1,true'
               
             ],
             'by_id'           =>   [
                'nullable',
                'numeric',
                'exists:variant_prices,id'
             ],
            'by_product_id'           =>   [
                'nullable',
                'numeric',
             ],
           
        ];
    }
}
