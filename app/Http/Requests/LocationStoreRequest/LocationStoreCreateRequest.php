<?php

namespace App\Http\Requests\LocationStoreRequest;

use Illuminate\Foundation\Http\FormRequest;

class LocationStoreCreateRequest extends FormRequest
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
            
            'name'                   =>   'required',
            'fk_province'            =>   'required',
            'image_upload'           =>   'nullable',
            'description'            =>   'required',
            'embed_map'              =>   'required',
    
        ];
    }

    public function messages()
    {
        return [

            'name.required'          => 'Nama perlu diisi',
            'fk_province.required'   => 'Provinsi perlu diisi',
            'description.required'   => 'Deskripsi perlu diisi',
            'embed_map.required'     => 'Embed map perlu diisi',

        ]; 
    }
}
