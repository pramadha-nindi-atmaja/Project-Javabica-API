<?php

namespace App\Http\Requests\BlogRequest;

use Illuminate\Foundation\Http\FormRequest;

class CreateBlogRequest extends FormRequest
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
        return  [
            'cover_upload'      =>   'required',  
            'title'             =>   'required',
            'short_desc'        =>   'required',
            'long_desc'         =>   'required',
            'fk_category'       =>   'required',
            'status'            =>   'required',
            'slug'              =>   'required',
            'hot_news'          =>   'required',
            'meta_title'        =>   'required',
            'meta_description'  =>   'required',
            
        ];
    }
}
