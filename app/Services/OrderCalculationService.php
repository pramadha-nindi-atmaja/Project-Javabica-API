<?php

namespace App\Services;

use App\Http\Controllers\BaseController;
use App\Models\Order;
use App\Models\Order_product;
use App\Models\Voucher;

/**
 * Class OrderProductCalculationService
 * @package App\Services
 */
class OrderCalculationService extends BaseController
{
    
    public function orderCalculation($orderId) {
        
        $getOrderProduct =  Order_product::where('fk_order_id',$orderId)->get();
        $subTotal        =  0;
        $total_qty       =  0;
        $voucherAmount   =  0;

        $getOrder = Order::where('id', $orderId)->first();
        $voucher = Voucher::where('id', $getOrder->fk_voucher_id)->first();

        foreach($getOrderProduct as $productOrder)
        {               
            $getTotal    = $productOrder->purchase_price*$productOrder->qty;
            $total_qty    = $total_qty+$productOrder->qty;
            $subTotal    = $subTotal+$getTotal;
        }

        //shipping
        $getShipping     = Order::where('id',$orderId)->first();
        $shippingPrice   = $getShipping->courier_cost;

        //grandTotal
        if($getOrder->fk_voucher_id != NULL && $getOrder->fk_voucher_id != ''){
            if($voucher->type == 1)
            {
                $voucherAmount  = $voucher->amount;
                $grandTotal     = $shippingPrice+$subTotal - $voucherAmount;
            }
            else
            {
                $voucherAmount  = (($subTotal) * ($voucher->amount / 100));
                $grandTotal     = $shippingPrice+$subTotal - $voucherAmount;
            }
        }
        else{
            $grandTotal      = $shippingPrice+$subTotal;
        }

        $calculation =  array(
            'sub_total'      => $subTotal,
            'total_item'     => $total_qty,
            'total_product'  => count($getOrderProduct),
            'shipping_total' => $shippingPrice,
            'discount'       => $voucherAmount,
            'grand_total'    => $grandTotal 
        );

        return $this->handleArrayResponse($calculation,'order calculate success','info');
       
    }
}
