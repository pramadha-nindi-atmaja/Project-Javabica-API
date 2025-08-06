<?php

namespace App\Http\Requests\RajaOngkirRequest;

use Illuminate\Foundation\Http\FormRequest;

class GetCityRequest extends FormRequest
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
            'id'          => 'nullable|numeric',
            'province_id'    => 'nullable|numeric',
        ];
    }
}
