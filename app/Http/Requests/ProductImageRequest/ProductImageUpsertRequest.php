<?php

namespace App\Http\Requests\ProductImageRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductImageUpsertRequest extends FormRequest
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

        if (Auth::user()->can('product_images_upsert')) {
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
            'data.*.id'                  =>   'nullable', 
            'data.*.fk_product_id'       =>   'required|exists:products,id', 
            'data.*.path'                =>   'required', 
            'data.*.order_number'         =>   'nullable|numeric', 
        ];
    }
}
