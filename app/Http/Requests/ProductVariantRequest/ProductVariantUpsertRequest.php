<?php

namespace App\Http\Requests\ProductVariantRequest;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule as ValidationRule;

class ProductVariantUpsertRequest extends FormRequest
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

        if (Auth::user()->can('product_variant_upsert')) {
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
            'data.*.id'                      =>   'nullable', 
            'data.*.fk_product_id'           =>   'required|exists:products,id', 
            'data.*.fk_attribute_parent_id'  =>   'nullable|exists:taxo_lists,id,taxonomy_type,6', 
            'data.*.fk_attribute_child_id'   =>   'nullable|exists:taxo_lists,id,taxonomy_type,6', 
           'data.*.sku'                     =>   'required|unique:product_variants,sku,product_variants*id,id,sku,'.$this->id, //https://paste.laravel.io/70e20397-4032-4af3-9d5a-bcd615883c44 
          
            'data.*.image_path'              =>   'nullable', 
          
            'data.*.price'                   =>   'required|integer', 
            'data.*.discount'                =>   'nullable|integer', 
            
            'data.*.stock'                   =>   'required_if:data.*.is_ignore_stock,INACTIVE|numeric', 
            'data.*.status'                  =>   'required|in:ACTIVE,INACTIVE', 
            'data.*.is_ignore_stock'         =>   'nullable|in:ACTIVE,INACTIVE', 
        ];
    }
}
