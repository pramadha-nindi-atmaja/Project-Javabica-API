<?php

namespace App\Http\Requests\MachineRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Machine;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class DestroyMachineRequest extends FormRequest
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
        
        if (Auth::user()->can('order_destroy')) {
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
            'by_id'               =>   'required|exists:machines,id'
        ];
    }
    public function messages()
    {
        return [

            'by_id.required'      => 'machine id perlu diisi',
            'by_id.exists'        => 'machine id tidak tersedia',

        ]; 
    }
}