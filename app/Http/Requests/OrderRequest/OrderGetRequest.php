<?php

namespace App\Http\Requests\OrderRequest;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderGetRequest extends FormRequest
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

        if (Auth::user()->can('order_show')) {
            return true;
        }

        if ($this->by_email == Auth::user()->email) {
           return true;
        }

        if ($this->by_user == Auth::user()->id) {
            return true;
        }
       
        if ($this->by_id) {
         
            $getOrder = Order::find($this->by_id);
           
            if($getOrder) {

                if($getOrder->fk_user_id == Auth::user()->id)
                {
                    return true;
                }
                
                if($getOrder->contact_email == Auth::user()->email)
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
            'keyword'         =>   [
                'nullable',
             ],
            'sort_by'          =>   [
                'nullable',
                'in:shipping_first_name,contact_email,order_number,contact_phone'
             ],
            'sort_type'       =>   [
                'nullable',
                'in:asc,desc',
                'required_with:sort_by'
             ],
            'paginate'       =>   [
                'nullable',
                'boolean',
                'required_with:page,per_page',
             ],
            'per_page'        =>   [
                'nullable',
                'numeric',
                'required_with:paginate',
                ' required_if:paginate,1,true'
             ],
            'page'           =>   [
                'nullable',
                'numeric',
                'required_with:paginate,per_page',
                ' required_if:paginate,1,true'
               
             ],
            'by_id'           =>   [
                'nullable',
                'numeric',
                'exists:orders,id'
             ],
             'by_email'           =>   [
                'nullable',
             ],
             'by_user'           =>   [
                'nullable',
                'numeric',
                'exists:orders,fk_user_id'
             ],
             'by_order_number'           =>   [
                'nullable',
                'exists:orders,order_number'
             ],

        ];
    }
}
