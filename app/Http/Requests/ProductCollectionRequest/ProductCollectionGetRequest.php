<?php

namespace App\Http\Requests\ProductCollectionRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCollectionGetRequest extends FormRequest
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

        if (Auth::user()->can('product_collection_show')) {
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
                'in:product_name,product_tags,product_status,'
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
                'exists:product_collections,id'
             ],
            'by_product_id'           =>   [
                'nullable',
                'numeric',
             ],
             'fk_collection_id'           =>   [
                'nullable',
                'numeric',
                'exists:product_collections,fk_collection_id'
             ],
             
        ];
    }
}
