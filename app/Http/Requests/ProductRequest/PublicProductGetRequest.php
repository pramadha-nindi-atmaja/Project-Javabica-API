<?php

namespace App\Http\Requests\ProductRequest;

use Illuminate\Foundation\Http\FormRequest;

class PublicProductGetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
                'in:name,status,product_price,product_discount'
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
                ' required_if:paginate,1,true'

            ],
            'by_id'           =>   [
                'nullable',
                'numeric',
                'exists:products,id'
            ],
            'by_collection_id'           =>   [
                'nullable',
                'numeric',
             
            ],
            'by_category_id'           =>   [
                'nullable',
                'numeric',
             
            ],
            'collection_type'           =>   [
                'nullable',
                'numeric',
             
            ],
            'is_detail'           =>   [
                'nullable',
                'boolean',
            ],
            'except_product_id'           =>   [
                'nullable',
                'numeric',
              
            ],
        ];
    }
    protected function passedValidation()
    {

        $this->merge([
            'by_status'            => 'PUBLISH',
        ]);

    }
}
