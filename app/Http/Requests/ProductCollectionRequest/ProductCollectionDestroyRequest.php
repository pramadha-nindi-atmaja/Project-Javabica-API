<?php

namespace App\Http\Requests\ProductCollectionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCollectionDestroyRequest extends FormRequest
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

        if (Auth::user()->can('product_collection_destroy')) {
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
            'by_id'                 =>   'required|exists:product_collections,id'
        ];
    }
    public function messages()
    {
       return [

        'by_id.required'               => 'product collections id perlu diisi',
        'by_id.exists'                 => 'product collections id tidak tersedia',

       ]; 
    }
}
