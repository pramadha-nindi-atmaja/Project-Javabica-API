<?php

namespace App\Http\Requests\ProductRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCreateRequest extends FormRequest
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

        if (Auth::user()->can('product_create')) {
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
            'name'                   =>   'required',
            'is_freeshiping'         =>   'required|in:ACTIVE,INACTIVE',
            'slug'                   =>   'required|alpha_dash|unique:products,slug',  
            'product_description'    =>   'nullable',
            'product_information'    =>   'nullable',
            'meta_keywords'          =>   'nullable',  
            'meta_description'       =>   'nullable',
            'meta_title'             =>   'nullable',  

            'weight'                 =>   'required',
            'type_weight'            =>   'required|in:GRAM,KG',  

            'size_long'              =>   'required|integer',
            'size_tall'              =>   'required|integer',
            'size_wide'              =>   'required|integer',

            'type_size'              =>   'required|in:CM,M',  
            'sort'                   =>   'nullable|integer',  

            'tags'                   =>   'nullable',
            'status'                 =>   'nullable|in:PUBLISH,INACTIVE,DRAFT',   
        ];
    }
    protected function passedValidation() {
        if(count($this->tags) >= 1)
        {
            $this->merge([
                'tags'          => implode(',', $this->tags),
            ]);
        }
        else {
            $this->merge([
                'tags'          => null,
            ]);
        }

        if(count($this->meta_keywords) >= 1)
        {
            $this->merge([
            
                'meta_keywords' => implode(',', $this->meta_keywords)
            ]);
        }
        else {
            $this->merge([
             
                'meta_keywords' => null
            ]);
        }
    }
}
