<?php

namespace App\Http\Requests\TaxonomyRequest;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaxonomyCreateRequestValidation extends FormRequest
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

        if(Auth::user()->can('taxonomy_create')) {
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
            'parent'             =>   'nullable|numeric|exists:taxo_lists,id',
            'taxonomy_ref_key'   =>   'nullable|numeric',
            'taxonomy_name'      =>   'required',
            'taxonomy_description'=>   'nullable',
            'taxonomy_slug'      =>   'nullable',
            'taxonomy_type'      =>   'required|numeric|exists:taxo_types,id',
            'taxonomy_image_upload'  => 'nullable',
            'taxonomy_sort'      =>   'nullable|numeric',
            'taxonomy_status'    =>   'nullable|in:ACTIVE,INACTIVE',

        ];
    }
    public function messages()
    {
       return [
        'parent.numeric'            => 'parent hanya dapat number',
        'parent.exists'             => 'taxonomy parent tidak ada id yang tersedia',

        'taxonomy_ref_key.numeric'  => 'taxonomy refrence key hanya dapat nomer',

        'taxonomy_name.required'    => 'name perlu diisi',
        'taxonomy_type.required'    => 'taxonomy type perlu diisi',
        'taxonomy_type.numeric'     => 'taxonomy type hanya dapat nomer',
        'taxonomy_type.exists'      => 'taxonomy type tidak id tidak tersedia',

        'taxonomy_sort.numeric'     => 'taxonomy sort hanya dapat nomor',


        'taxonomy_status.in'        => 'hanya dapat ACTIVE,INACTIVE',

       ];
    }
}
