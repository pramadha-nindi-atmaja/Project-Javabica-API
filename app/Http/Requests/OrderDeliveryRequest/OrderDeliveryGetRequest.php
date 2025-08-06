<?php

namespace App\Http\Requests\OrderDeliveryRequest;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderDeliveryGetRequest extends FormRequest
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
        
        if(Auth::user()->can('order_delivery_create')) {
            return true;
        }

        $getOrder = Order::find($this->by_id);

            if($getOrder) {

                if($getOrder->fk_user_id == Auth::user()->id)
                {
                    return true;
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
           
             'by_id'           =>   [
                'required',
                'exists:orders,id'
             ],
        ];
    }
}
