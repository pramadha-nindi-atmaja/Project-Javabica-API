<?php

namespace App\Http\Requests\VoucherRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Voucher;
use Illuminate\Validation\ValidationException;

class VoucherDestroyRequest extends FormRequest
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
            'by_id'               =>   'required|exists:vouchers,id',
        ];
    }
    public function messages()
    {
        return [

            'by_id.required'      => 'voucher id perlu diisi',
            'by_id.exists'        => 'voucher id tidak tersedia',

        ]; 
    }
    protected function passedValidation() {

        //check if id registered as parent
        $checkdata =  Voucher::where('id',$this->by_id)->first();

        if(!$checkdata) {
            throw ValidationException::withMessages([
                'id' => ['destory fail, voucher does not match'],
            ]);
        }
    }
}