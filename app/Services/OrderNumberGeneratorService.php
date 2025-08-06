<?php

namespace App\Services;

use App\Http\Controllers\BaseController;
use App\Models\Order;
use Carbon\Carbon;

/**
 * Class OrderNumberGeneratorService
 * @package App\Services
 */
class OrderNumberGeneratorService extends BaseController
{
    public function generate() {

        $month               = date("m");
        $year                = date("Y");
        $initiateOrderNumber = 101;

        if(config('app.env') == 'local' || config('app.env') == 'staging')
        {
            $prefixInvoice       = config('app.env').'/'.rand(1,999).date('is').'/JVC/WEB/INV-'.$year.'/'.$month.'/';
        }
        else
        {
            $prefixInvoice       = 'JVC/WEB/INV-'.$year.'/'.$month.'/';
        }

        $getQueueNumber = Order::select('queue_number')->whereYear('created_at',$year)->whereMonth('created_at', $month)->orderBy('queue_number','desc')->first();

        if($getQueueNumber) {

                $queue_number = $getQueueNumber->queue_number+1;
        }
        else {

                $queue_number = $initiateOrderNumber; //reset from 1
        }
        
        $returnValue = array(
            'queue_number'   => $queue_number,
            'invoice_number' =>  $prefixInvoice.$queue_number
        );

        return $this->handleArrayResponse($returnValue,'service generate invoice number success','info');
    
    }
}
