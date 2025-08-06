<?php

namespace App\Http\Requests\OrderRequest;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderCreateRequest extends FormRequest
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
            
           
            'contact_email'                   =>   'required|email',
            'contact_phone'                   =>   'required',  
            
            'shipping_country'                =>   'required',
            'shipping_first_name'             =>   'required',
            'shipping_last_name'              =>   'required',  
            'shipping_address'                =>   'required',
            'shipping_city'                   =>   'required',  
            'shipping_province'               =>   'required',
            'shipping_postal_code'            =>   'required', 
            'shipping_label_place'            =>    'nullable', 
            'shipping_note_address'           =>    'nullable', 

            'contact_billing_phone'           =>   'required',   
            'billing_country'                =>   'required',
            'billing_first_name'             =>   'required',
            'billing_last_name'              =>   'required',  
            'billing_address'                =>   'required',
            'billing_city'                   =>   'required',  
            'billing_province'               =>   'required',
            'billing_postal_code'            =>   'required', 
            'billing_label_place'            =>    'nullable', 
            'billing_note_address'           =>    'nullable',  

            'courier_agent'                   =>   'required',
            'courier_agent_service'           =>   'required',
            'courier_agent_service_desc'      =>   'required',
            'courier_estimate_delivered'      =>   'required',  
            'courier_resi_number'             =>   'nullable',
            'courier_cost'                    =>   'nullable|integer', 
         
            'payment_method'                  =>   'required',
            'payment_refrence_code'           =>   'nullable',

            'invoice_note'                    =>   'nullable',
            'delivery_order_note'           =>   'nullable',

            'fk_user_id'                      =>   'nullable',
            'status'                          =>   'required|in:ORDER,PAYMENT,PROCESS,SHIPPED,COMPLETE',  
            'payment_status'                  =>   'nullable'
        ];
    }
}
