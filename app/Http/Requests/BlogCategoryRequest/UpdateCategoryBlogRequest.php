<?php

namespace App\Http\Requests\BlogCategoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryBlogRequest extends FormRequest
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
            'name'        => 'required',
            'slug'        => 'nullable|unique:category_blogs,slug,' . $this->id,
            'description' => 'nullable',
            'status'      => 'required|boolean',
            'id'          => 'required|exists:category_blogs,id', 
        ];
    }
}