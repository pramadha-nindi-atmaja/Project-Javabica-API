<?php

namespace App\Http\Requests\RajaOngkirRequest;

use Illuminate\Foundation\Http\FormRequest;

class GetCostRequest extends FormRequest
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
            'origin'         => 'nullable|numeric',
            'destination'    => 'required|numeric',
            'weight'         => 'required|numeric',
            'courier'        => 'nullable|in:jne', //in:jne,pos,tiki "ini adalah list yang available"
        ];
    }
    protected function passedValidation() {

        //merge request for order product and order
        $this->merge([
            'origin'    => config('rajaongkir.originCity')
        ]);
       
    }
}
