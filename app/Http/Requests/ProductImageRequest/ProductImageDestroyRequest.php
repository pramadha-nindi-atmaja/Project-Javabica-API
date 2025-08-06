<?php

namespace App\Http\Requests\ProductImageRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductImageDestroyRequest extends FormRequest
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

        if (Auth::user()->can('product_images_destroy')) {
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
            'by_id'                 =>   'required'
        ];
    }
    public function messages()
    {
       return [

        'by_id.required'               => 'product images id perlu diisi',
        'by_id.exists'                 => 'product images id tidak tersedia',

       ]; 
    }
}
