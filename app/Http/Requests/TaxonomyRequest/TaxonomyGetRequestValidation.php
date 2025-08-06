<?php

namespace App\Http\Requests\TaxonomyRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaxonomyGetRequestValidation extends FormRequest
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
                'in:taxonomy_name,taxonomy_type_name,taxonomy_sort,created_at'
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
                'exists:taxo_lists,id'
             ],
             'by_parent'           =>   [
                'nullable',
                'numeric',
             ],
             'by_taxo_type_id'       =>   [
                'nullable',
                'numeric',
                'exists:taxo_types,id'
             ],
             'only_parent'       =>   [
                'nullable',
                'boolean',
             ],
             'get_category_and_subcategory'       =>   [
               'nullable',
               'boolean',
            ],

            'multi_taxo_type_id'       =>   [
               'nullable',
            
            ],
             
        ];
    }
}
