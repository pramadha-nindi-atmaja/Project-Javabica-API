<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Order_product;
use Illuminate\Support\Facades\DB;

/**
 * Class CheckoutTransactionStoreServices
 * @package App\Services
 */
class CheckoutTransactionStoreServices
{
    public function checkoutTransaction($order,$orderProduct) {

     
            // Transaction
            DB::beginTransaction();
 
            try{
                // Step 1 : Create User
                $order = new Order();
                $order->queue_number                 = $order['queue_number'];
                $order->order_number                 = $order['order_number'];
                
                $order->contact_email                = $order['contact_email'];
                $order->contact_phone                = $order['contact_phone'];
                $order->shipping_country             = $order['shipping_country'];
                $order->shipping_first_name          = $order['shipping_first_name'];
                $order->shipping_last_name           = $order['shipping_last_name'];
                $order->shipping_address             = $order['shipping_address'];
                $order->shipping_city                = $order['shipping_city'];
                $order->shipping_province            = $order['shipping_province'];
                $order->shipping_postal_code         = $order['shipping_postal_code'];
              
                $order->courier_agent                = $order['courier_agent'];
                $order->courier_agent_service        = $order['courier_agent_service'];
                $order->courier_agent_service_desc   = $order['courier_agent_service_desc'];
                $order->courier_estimate_delivered   = $order['courier_estimate_delivered'];
                $order->courier_resi_number          = $order['courier_resi_number'];
                $order->courier_cost                 = $order['courier_cost'];

                $order->payment_method               = $order['payment_method'];
                $order->payment_refrence_code        = $order['payment_refrence_code'];
                $order->fk_user_id                   = $order['fk_user_id'];

                $order->payment_status               = $order['payment_status'];
                $order->status                       = $order['status'];
                
                $order->save();
     
                //Step 2 : Amount Charged
                $orderProduct                   = new Order_product();
                $orderProduct->fk_product_id    = $user->id;
                $orderProduct->product_name     = $user->id;
                $orderProduct->image            = $user->id;
                $orderProduct->sku              = $user->id;
                $orderProduct->attribute_parent = $user->id;
                $orderProduct->attribute_child  = $user->id;
                $orderProduct->qty              = $user->id;
                $orderProduct->acctual_price    = $user->id;
                $orderProduct->discount_price   = $user->id;
                $orderProduct->purchase_price   = $user->id;
                $orderProduct->purchase_price   = $user->id;
                $orderProduct->fk_order_id      = $user->id;
        
                $orderProduct->save();
     
                DB::commit();
     
                return redirect()->route('user.index')->with('success','Thank You for your subscription');
     
            }catch(\Exception $e){
                DB::rollback();
                return redirect()->route('user.index')
                            ->with('warning','Something Went Wrong!');
            }
    }
}
