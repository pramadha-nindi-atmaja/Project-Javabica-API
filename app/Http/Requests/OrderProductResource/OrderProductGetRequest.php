<?php

namespace App\Http\Requests\OrderProductResource;

use App\Models\Order;
use App\Models\Order_product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderProductGetRequest extends FormRequest
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
        
        if(Auth::user()->can('order_product_show')) {
            return true;
        }

        
       // return true;

        if($this->by_order_id != null) {
          
            //define user owner
            $getUser  = Order::find($this->by_order_id);

            if($getUser->fk_user_id ==  Auth::user()->id)
            {
                return true;
            }

        }

        if($this->by_id != null) {

            //define user owner
            $getUser =  Order_product::find($this->by_id)->fk_order->fk_user_id;

            if($getUser) {

                if($getUser == Auth::user()->id)
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
                'in:sku,attribute_parent,attribute_child,qty,acctual_price,discount_price,purchase_price'
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
                'exists:order_products,id'
             ],
             'by_order_id'           =>   [
                'nullable',
                'numeric',
                'exists:order_products,fk_order_id'
             ],
        
        ];
    }
}
