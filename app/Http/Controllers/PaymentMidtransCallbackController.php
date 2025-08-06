<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\HistoryVoucher;
use App\Services\Midtrans\CallBackService;
use Illuminate\Http\Request;

class PaymentMidtransCallbackController extends Controller
{
    public function create() {
       
        $callback = new CallBackService;
        
        if ($callback->isSignatureKeyVerified()) {
            $notification = $callback->getNotification();
            $order = $callback->getOrder();
 
            if ($callback->isSuccess()) {
                Order::where('id', $order->id)->update([
                    'payment_status' => 'SUCCESS',
                    'payment_refrence_code' =>$notification->transaction_id,
                    'payment_method'    =>$notification->payment_type,
                ]);
            }
 
            if ($callback->isExpire()) {
                Order::where('id', $order->id)->update([
                    'payment_status' => 'EXPIRED',
                    'payment_refrence_code' =>$notification->transaction_id,
                    'payment_method'    =>$notification->payment_type,
                ]);
                HistoryVoucher::where('order_id', $order->id)->delete();
            }

            if ($callback->isPending()) {
                Order::where('id', $order->id)->update([
                    'payment_status' => 'PENDING',
                    'payment_refrence_code' =>$notification->transaction_id,
                    'payment_method'    =>$notification->payment_type,
                ]);
            }
 
            if ($callback->isCancelled()) {
                Order::where('id', $order->id)->update([
                    'payment_status' => 'CANCEL',
                    'payment_refrence_code' =>$notification->transaction_id,
                    'payment_method'    =>$notification->payment_type,
                ]);
                HistoryVoucher::where('order_id', $order->id)->delete();
            }
 
            return response()
                ->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil diproses',
                ]);
        } else {
            return response()
                ->json([
                    'error' => true,
                    'message' => 'Signature key tidak terverifikasi',
                ], 403);
        }
    }
    
}
