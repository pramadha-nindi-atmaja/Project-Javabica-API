<?php

namespace App\Http\Requests\LocationStoreRequest;

use Illuminate\Foundation\Http\FormRequest;

class LocationStoreDestroyRequest extends FormRequest
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
            'by_id'                 =>   'required|exists:location_stores,id'
        ];
    }
}
