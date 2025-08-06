<?php

namespace App\Http\Requests\BlogRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Blog;
use  Illuminate\Validation\ValidationException;

class DestroyBlogRequest extends FormRequest
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
            'by_id'                 =>   'required|exists:blogs,id',
            'title'                 =>   'required'
        ];
    }
    public function messages()
    {
        return [

            'by_id.required'        => 'blog id perlu diisi',
            'by_id.exists'          => 'blog id tidak tersedia',

        ]; 
    }
    protected function passedValidation() {

        //check if id registered as parent

        $checkdata =  Blog::where('id',$this->by_id)->where('title',$this->title)->first();

        if(!$checkdata) {
            throw ValidationException::withMessages([
                'title' => ['destory fail,blog title not match'],
            ]);
        }
    }
}