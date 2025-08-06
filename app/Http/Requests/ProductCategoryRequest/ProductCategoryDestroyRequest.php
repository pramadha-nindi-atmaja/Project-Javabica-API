<?php

namespace App\Http\Requests\ProductCategoryRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCategoryDestroyRequest extends FormRequest
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

        if (Auth::user()->can('product_category_destroy')) {
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
            'by_id'                 =>   'required|exists:product_categories,id'
        ];
    }
    public function messages()
    {
       return [

        'by_id.required'               => 'product category id perlu diisi',
        'by_id.exists'                 => 'product category id tidak tersedia',

       ]; 
    }
}
