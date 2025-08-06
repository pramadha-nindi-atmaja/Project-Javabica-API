<?php

namespace App\Http\Requests\MachineRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateMachineRequest extends FormRequest
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
        
        if (Auth::user()->can('order_update')) {
            return true;
        }
        
        if ($this->by_email == Auth::user()->email) {
            return true;
        }
        
        if ($this->by_id) {
        
            $getMachine = Machine::find($this->by_id);
        
            if($getMachine) {

                if($getMachine->user_id == Auth::user()->id)
                {
                    return true;
                }                
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'product_id'        => 'required',
            'serial_number'     => 'required',
            'purchase_date'     => 'nullable',
            'description'       => 'nullable',
            'id'                => 'required|exists:machines,id', 
        ];
    }
}