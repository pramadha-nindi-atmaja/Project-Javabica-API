<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest\CheckoutCreateRequest;
use App\Interfaces\OrderInterface;
use App\Models\Order;
use App\Models\Voucher;
use App\Models\HistoryVoucher;
use App\Models\Order_product;
use App\Models\User_shipping_address;
use App\Services\Cart\CheckingCartPerItemWithSummaryGroupingService;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\OrderCalculationService;
use App\Services\OrderNumberGeneratorService;
use App\Services\OrderReduceStockServices;
use App\Services\RajaOngkir\CostServices;
use App\Services\RajaOngkir\CourierFinderServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class CheckoutController extends BaseController
{
   

    private $checkingCartPerItemWithSummaryGroupingService;


    public function __construct(CheckingCartPerItemWithSummaryGroupingService $checkingCartPerItemWithSummaryGroupingService)
    {
        $this->checkingCartPerItemWithSummaryGroupingService            = $checkingCartPerItemWithSummaryGroupingService;
    }
     /**
     * @lrd:start
     * # contoh format request untuk upsert
     * # ubah kutip nya menjadi kutip string
     * =============
   *{
    * "data": {
    *     "shipping": {
    *         "address_id": 1
    *     },
    *    "billing": {
    *         "address_id": 1,
    *         "same_as_shipping": 1
    *     },
    *     "courier": {
    *         "agent":"jne",
    *         "service":"sam day",
    *         "price":13000,
    *        "etd":"4-5"
    *   },
    *  "product": [{
    *         "variant_id": 1,
    *        "qty": "asd",
    *       "note":""
    *   },
    *  {
    *     "variant_id": 1,
    *    "qty": "asd",
     *       "note":""
    *        }
    *    ]

    * }
    *}
    * =============
    * akhir dari format upsert
    * @lrd:end
    */
    public function create(
    CheckoutCreateRequest $request,
    CostServices $costService,
    CourierFinderServices $courierFinderService,
    OrderInterface $orderInterface,
    OrderNumberGeneratorService $orderNumberGenerator,
    CreateSnapTokenService $createSnapTokenService,
    OrderCalculationService $orderCalculationService,
    OrderReduceStockServices $orderReduceStockServices

    ) 
    {
        if($request->data['billing']['same_as_shipping'] == true)
        {
            $billingId     =  $request->data['shipping']['address_id'];
        }
        else
        {
            $billingId     =  $request->data['billing']['address_id'];
        }
        
       $shippingId    =  $request->data['shipping']['address_id'];
       $productOrder  =  $request->data['product'];
       $courier       =  $request->data['courier'];
       $voucher       =  $request->data['voucher'];
       //checking product stock and price
        
        $getVoucher = Voucher::where('id', $voucher)->first();

       $checkingdata =  $this->checkingCart($productOrder);
      
        if($checkingdata['arrayStatus'] == false && $checkingdata['arrayResponse']['out_of_stock'] >= 1)
        {
            return   $this->handleError($checkingdata['arrayResponse'],'there is item out of stock, please let customer know',$request->all(),str_replace('/','.',$request->path()),422);
        }
        if($checkingdata['arrayStatus'] == false && $checkingdata['arrayResponse']['cart'] <= 0)
        {
            return   $this->handleError($checkingdata['arrayResponse'],'cart is empty please input some product',$request->all(),str_replace('/','.',$request->path()),422);
        }
       

        //checking price raja ongkir

        $checkAddress = User_shipping_address::where('id',$shippingId)->where('fk_user_id',Auth::user()->id)->first();
     
        if($checkAddress != true)
        {
            $data  = array([
                'field'      =>'shipping address',
                'message'    => 'shipping address not found, please try other address'
            ]);

            return   $this->handleError($data,'shipping address not found',$request->all(),str_replace('/','.',$request->path()),422);
        }

        //billing address checking

        $billingAddress = User_shipping_address::where('id',$billingId)->where('fk_user_id',Auth::user()->id)->first();
     
        if($billingAddress != true)
        {
            $data  = array([
                'field'      =>'billing address',
                'message'    => 'billing address not found, please try other address'
            ]);

            return   $this->handleError($data,'billing address not found',$request->all(),str_replace('/','.',$request->path()),422);
        }
       
        //checking courier 

         $data = array(
                'origin'         => config('rajaongkir.originCity'),
                'destination'    => $checkAddress['city'],
                'weight'         => $checkingdata['arrayResponse']['calculation']['total_weight'],
                'courier'        => $courier['agent']
        );
        $checkCost = $costService->getCost($data);
      
        if($checkCost['arrayStatus'] == false)
        {
            $data  = array([
                'field'      =>'raja-ongkir-message',
                'message'    => $checkCost['arrayResponse']['response']['rajaongkir']['status']['description']
            ]);

            return   $this->handleError($data,'courier fail',$request->all(),str_replace('/','.',$request->path()),422);
        }

        //cost courier finder  

        $findCourier =  $courierFinderService->courierCostFinder($checkCost['arrayResponse'][0]['costs'],$courier);
      
        if($findCourier['arrayStatus'] != true)
        {
            $data  = array([
                'field'      =>'courier find fail',
                'message'    => 'please input right cost shipping'
            ]);

            return   $this->handleError($data,'cost courier shipping fail',$request->all(),str_replace('/','.',$request->path()),422);
        }

        //setelah semua koneksi external berjalan dengan baik maka lakukan pengecekan akhir dan reduce cost
        $checkingdata =  $this->checkingCart($productOrder);

        if($checkingdata['arrayStatus'] == false && $checkingdata['arrayResponse']['out_of_stock'] >= 1)
        {
            return   $this->handleError($checkingdata['arrayResponse'],'there is item out of stock, please let customer know',$request->all(),str_replace('/','.',$request->path()),422);
        }
        if($checkingdata['arrayStatus'] == false && $checkingdata['arrayResponse']['cart'] <= 0)
        {
            return   $this->handleError($checkingdata['arrayResponse'],'cart is empty please input some product',$request->all(),str_replace('/','.',$request->path()),422);
        }
        // reduce stock from variant
        $orderReduceStockServices->reduceStock($checkingdata['arrayResponse']['cart']);

       // store in order table
        $getOrderNumber = $orderNumberGenerator->generate();
    
        $payloadOrder = array(
            'queue_number'               => $getOrderNumber['arrayResponse']['queue_number'],
            'order_number'               => $getOrderNumber['arrayResponse']['invoice_number'],
            'uuid'                       => Str::uuid().'-'.date('Ymd-His'),
            
            'contact_email'              => Auth::user()->email,
            
            'shipping_country'           => 'Indonesia',
            'contact_phone'              => $checkAddress->phone_number,
            'shipping_first_name'        => $checkAddress->first_name,
            'shipping_last_name'         => $checkAddress->last_name,
            'shipping_address'           => $checkAddress->address,
            'shipping_city'              => $checkAddress->city_label,
            'shipping_province'          => $checkAddress->province_label,
            'shipping_postal_code'       => $checkAddress->postal_code,
            'shipping_label_place'       => $checkAddress->label_place,
            'shipping_note_address'      => $checkAddress->courier_note,

            'billing_country'            => 'Indonesia',
            'contact_billing_phone'      => $billingAddress->phone_number,
            'billing_first_name'         => $billingAddress->first_name,
            'billing_last_name'          => $billingAddress->last_name,
            'billing_address'            => $billingAddress->address,
            'billing_city'               => $billingAddress->city_label,
            'billing_province'           => $billingAddress->province_label,
            'billing_postal_code'        => $billingAddress->postal_code,
            'billing_label_place'        => $billingAddress->label_place,
            'billing_note_address'       => $billingAddress->courier_note,

            'courier_agent'              =>$courier['agent'],
            'courier_agent_service'      =>$findCourier['arrayResponse'][0]['service'],
            'courier_agent_service_desc' =>$findCourier['arrayResponse'][0]['description'],
            'courier_estimate_delivered' =>$findCourier['arrayResponse'][0]['cost'][0]['etd'],
            'courier_resi_number'        =>'',
            'courier_cost'               =>$findCourier['arrayResponse'][0]['cost'][0]['value'],
            
            'payment_method'             =>'Midtrans',
            'payment_refrence_code'      =>'',

            'invoice_note'               => ''.config('javabica.invoice_note').'',
            'delivery_order_note'        => ''.config('javabica.delivery_order_note').'',

            'fk_user_id'                 => Auth::user()->id,

            'fk_voucher_id'              => $voucher,

            'payment_status'             =>'UNPAID',
            'status'                     =>'ORDER'
        );

        $insert  =  $orderInterface->store($payloadOrder,'show_all');

        if($voucher != NULL){
            $historyVoucher = new HistoryVoucher();
            $historyVoucher->voucher_id = $voucher;
            $historyVoucher->user_id = Auth::user()->id;
            $historyVoucher->order_id = $insert['queryResponse']['data']['id'];
            $historyVoucher->save();
        }
        
        if($insert['queryStatus'] != true)
        {
            $data  = array([
                'field'      =>'error when insert order',
                'message'    => 'please call our admin'
            ]);

            return   $this->handleError($data,'insert order fail',$request->all(),str_replace('/','.',$request->path()),422);
        }
    
    
        $transformCart        = [];
        $transformforMidtrans = [];

        foreach($checkingdata['arrayResponse']['cart'] as $cart) {
            
            $data  = array(
                'fk_product_id'      =>  $cart['product_id'],
                'fk_variant_id'      =>  $cart['variant_id'],
                'product_name'       =>  $cart['product_name'],
                'image'              =>  $cart['product_image'],
                'sku'                =>  $cart['variant_sku'],
                'variant_description'=>  $cart['variant_description'],
                'qty'                =>  $cart['qty'],
                'acctual_price'      =>  $cart['price_info']['price'],
                'discount_price'     =>  $cart['price_info']['discount'],
                'purchase_price'     =>  $cart['purchase_price'],
                'note'               =>  $cart['note'],
                'fk_order_id'        =>  $insert['queryResponse']['data']['id'],
                'created_at'         =>  now(),
                'updated_at'         =>  now(),
            );

            //limit midtrans name
            $str = $cart['product_name'];
            $prodName =  (strlen($str) > 42) ? substr($str,0,42).'...' : $str;

            $dataMidtrans = array(
                'id'             => $cart['variant_sku'],
                'price'          => $cart['purchase_price'],
                'quantity'       => $cart['qty'],
                'name'           => $prodName,
            );

            array_push($transformCart,$data);
            array_push($transformforMidtrans,$dataMidtrans);

        }

        $create = Order_product::insert($transformCart);

        if($create) {
                //add shipping to midtrans 
                $shippingBill = array(
                    'id'             => 'shiping-'.$courier['agent'].'-'.$findCourier['arrayResponse'][0]['service'],
                    'price'          => $findCourier['arrayResponse'][0]['cost'][0]['value'],
                    'quantity'       => 1,
                    'name'           => $courier['agent'].'-'.$findCourier['arrayResponse'][0]['service'],
                );
                
                // customer information - midtrans
                $getCalculation = $orderCalculationService->orderCalculation($insert['queryResponse']['data']['id']);
                
                $voucherAmount = 0;
                $voucherBill = array(
                    'id'             => 'No Voucher',
                    'price'          => 0,
                    'quantity'       => 1,
                    'name'           => 'No Discount Voucher',
                );

                if($getVoucher)
                {
                    $voucherAmount  = -abs($getCalculation['arrayResponse']['discount']);
                    $voucherBill = array(
                        'id'             => $getVoucher->code,
                        'price'          => $voucherAmount,
                        'quantity'       => 1,
                        'name'           => 'Discount Voucher',
                    );
                }

                array_push($transformforMidtrans,$shippingBill,$voucherBill);
                $transaction_detail = array(
                    'order_id'      =>  $getOrderNumber['arrayResponse']['invoice_number'],
                    'gross_amount'  =>  $getCalculation['arrayResponse']['grand_total'],
                );

                $customerInfo = array(
                    'first_name' =>  Auth::user()->name,
                    'email'      => Auth::user()->email,
                    'phone'      => Auth::user()->phone,
                );

                //transaction - midtrans
                $payload = array(
                    'cart'                   =>  $transformforMidtrans,
                    'transaction_details'    =>  $transaction_detail,
                    'customer_details'       =>  $customerInfo
                );

                // process midtrans
                $midtransSnap = $createSnapTokenService->getSnapToken($payload);
                
                //update snap token 
                $snapToken = array(
                    'payment_snap_token'    =>$midtransSnap
                );

                $update  =  Order::find($insert['queryResponse']['data']['id']);
                $update->update($snapToken);

                $data = array(
                    'uuid'      =>$update->uuid,
                    'id'        => $update->id,
                    'payment_snap_token' =>$update->payment_snap_token
                );

                return $this->handleResponse($data,'checkout success',$request->all(),str_replace('/','.',$request->path()),201);
            }
            else {
                $data  = array([
                    'field'      =>'error-order-product',
                    'message'    => 'oops something error when insert, please contact administrator'
                ]);

                return   $this->handleError($data,'error when insert order', $request->all(), str_replace('/', '.', $request->path()), 422);
            }
   
    }
    private function checkingCart($productOrder) {

         //checking product stock and price
       $checkingdata = $this->checkingCartPerItemWithSummaryGroupingService->groupingPerItem($productOrder);
     
       if(count($checkingdata['arrayResponse']['out_of_stock']) >= 1)
       {
           return $this->handleArrayErrorResponse($checkingdata['arrayResponse'],'item out of stock','info');
        }
       if(count($checkingdata['arrayResponse']['cart']) <= 0)
       {
           $data  = array([
               'field' =>'cart empty',
               'message'=> 'cart is empty, please input some product'
           ]);

           return $this->handleArrayErrorResponse($data,'cart is empty please input some product','info');
        }

        return $this->handleArrayResponse($checkingdata['arrayResponse'],'checking success','info');

    }
}
