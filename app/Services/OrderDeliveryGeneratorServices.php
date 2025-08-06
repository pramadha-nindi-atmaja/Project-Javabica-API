<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;

/**
 * Class OrderInvoiceGeneratorService
 * @package App\Services
 */
class OrderDeliveryGeneratorServices
{
    public function generate($dataCollection)
    {
        $companyDetail = new Party([
            'coporate_name'        => ''.config('javabica.corporate_name').'',
            'address'              => ''.config('javabica.address').'',
            'phone'                => ''.config('javabica.phone').'',
            'email'                => ''.config('javabica.email').'',
            'website'              => ''.config('javabica.website').'',
            
        ]);

        $customer = new Party([
            'name'          => $dataCollection->shipping_first_name . ' ' . $dataCollection->shipping_last_name,
            'custom_fields' => [
                'Alamat' => $dataCollection->shipping_address . ', ' . $dataCollection->shipping_city . ', ' . $dataCollection->shipping_province . ', ' . $dataCollection->shipping_postal_code . ', ' . $dataCollection->shipping_country . '.',
                'Telp' => $dataCollection->contact_phone,
                'catatan untuk kurir' => $dataCollection->shipping_note_address,
            ],
        ]);

        $items = [];
        $totalProduct = count($dataCollection->product_order );
        $totalItem=0;
        foreach ($dataCollection->product_order as $cart) {
            $listCart =   (new InvoiceItem())
                ->title($cart->product_name)
                ->description(''.$cart->variant_description.'')
                ->quantity($cart->qty);
                $listCart->product_code = $cart->sku;
                $listCart->note         = $cart->note;
                $totalItem              = $totalItem+$cart->qty;

   
            if ($cart->discount_price != null || $cart->discount_price > 0) {

                $listCart->sub_total_price = $cart->qty*$cart->discount_price;
                $reformat                  =  $cart->qty*($cart->acctual_price-$cart->discount_price);
                $listCart                  = $listCart->pricePerUnit($cart->acctual_price);
                $listCart                  = $listCart->discount($reformat);
            }

            else
            {
                $listCart=$listCart->pricePerUnit($cart->purchase_price);
            }

            array_push($items, $listCart);
        }
  
        $notes = [
            '',
            '',
            '' . $dataCollection->delivery_order_note . '',

        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('Invoice')
            ->template('deliveryOrder')
            ->status($dataCollection->payment_status)
            ->serialNumberFormat($dataCollection->order_number)
            ->buyer($customer)
            ->date(Carbon::parse($dataCollection->created_at))
            ->dateFormat('d/m/Y')
            ->currencySymbol('Rp')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($customer->name.'_'.$dataCollection->order_number)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('brand/logo_blue.png'));
            // You can additionally save generated invoice to configured disk
            //  ->save('public');
            //  $link = $invoice->url();
      
            $invoice->shipping_amount        =  $dataCollection->courier_cost;
            $invoice->total_product          =  $totalProduct;
            $invoice->total_item             =  $totalItem;
            $invoice->companyDetail          =  $companyDetail;
            $invoice->ekspedisi       =  $dataCollection->courier_agent.'-'.$dataCollection->courier_agent_service;
            
        
            // And return invoice itself to browser or have a different view
            return $invoice->stream();
    }
}
