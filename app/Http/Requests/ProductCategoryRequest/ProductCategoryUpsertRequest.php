<?php

namespace App\Http\Requests\ProductCategoryRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCategoryUpsertRequest extends FormRequest
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

        if (Auth::user()->can('product_category_upsert')) {
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
            'data.*.id'                                  =>   'nullable', 
            'data.*.fk_product_id'                       =>   'required|exists:products,id', 
            'data.*.fk_category_id'                    =>   'required|exists:taxo_lists,id', 
        ];
    }
}
