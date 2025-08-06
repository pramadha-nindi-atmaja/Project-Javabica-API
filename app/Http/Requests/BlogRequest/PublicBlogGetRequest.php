<?php

namespace App\Http\Requests\BlogRequest;

use Illuminate\Foundation\Http\FormRequest;

class PublicBlogGetRequest extends FormRequest
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
            'sort_type'       =>   [
                'nullable',
                'in:asc,desc',
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
            'category_id'      =>   [
                'nullable',
                'string',
            ],
            'hot_news'      =>   [
                'nullable',
                'boolean',
            ],
            'status'         => [
                'nullable',
                'boolean',
            ],

            

        ];
    }
}
