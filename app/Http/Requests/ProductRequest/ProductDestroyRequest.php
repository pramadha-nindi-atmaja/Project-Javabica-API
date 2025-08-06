<?php

namespace App\Http\Requests\ProductRequest;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use  Illuminate\Validation\ValidationException;
class ProductDestroyRequest extends FormRequest
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

        if (Auth::user()->can('product_destroy')) {
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
        return [
            'by_id'                        =>   'required|exists:products,id',
            'product_name'                 =>   'required'
        ];
    }
    public function messages()
    {
       return [

        'by_id.required'               => 'product id perlu diisi',
        'by_id.exists'                 => 'product id tidak tersedia',

       ]; 
    }
    protected function passedValidation() {

        //check if id registered as parent

        $checkdata =  Product::where('id',$this->by_id)->where('name',$this->product_name)->first();

        if(!$checkdata) {
            throw ValidationException::withMessages([
                'title' => ['destory fail,product name not match'],
            ]);
        }
    }
}
