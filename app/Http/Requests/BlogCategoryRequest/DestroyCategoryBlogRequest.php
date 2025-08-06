<?php

namespace App\Http\Requests\BlogCategoryRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CategoryBlog;
use  Illuminate\Validation\ValidationException;

class DestroyCategoryBlogRequest extends FormRequest
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
            'by_id'               =>   'required|exists:category_blogs,id',
            'name'                =>   'required'
        ];
    }
    public function messages()
    {
        return [

            'by_id.required'      => 'kategori id perlu diisi',
            'by_id.exists'        => 'kategori id tidak tersedia',

        ]; 
    }
    protected function passedValidation() {

        //check if id registered as parent
        $checkdata =  CategoryBlog::where('id',$this->by_id)->where('name',$this->name)->first();

        if(!$checkdata) {
            throw ValidationException::withMessages([
                'name' => ['destory fail,category name not match'],
            ]);
        }
    }
}