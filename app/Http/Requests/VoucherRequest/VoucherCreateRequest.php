<?php

namespace App\Http\Requests\VoucherRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VoucherCreateRequest extends FormRequest
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
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return  [
            'code'          => 'nullable|unique:vouchers,code|alpha_num',
            'description'   => 'required',
            'type'          => 'required',
            'amount'        => 'required',
            'start_date'    => 'nullable|date',
            'end_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) {
                    $start_date = $this->input('start_date');
    
                    if ($start_date && $value && strtotime($start_date) > strtotime($value)) {
                        $fail('End Date harus lebih besar atau sama dengan Start Date.');
                    }
                },
            ],
            'max_usage'     => 'nullable',
            'is_active'     => 'required',
        ];
    }
}
