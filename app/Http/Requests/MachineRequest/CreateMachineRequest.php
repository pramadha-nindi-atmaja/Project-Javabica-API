<?php

namespace App\Http\Requests\MachineRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateMachineRequest extends FormRequest
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
        
        if (Auth::user()->can('order_create')) {
            
            return true;
        }
        
        if ($this->by_email == Auth::user()->email) {
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
            'product_id'        => 'required',
            'serial_number'     => 'required',
            'purchase_date'     => 'nullable',
            'description'       => 'nullable',
        ];
    }
}
