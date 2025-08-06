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
class OrderInvoiceGeneratorService
{
    public function generate($dataCollection)
    {
        try {
            $companyDetail = new Party([
                'coporate_name' => utf8_encode(config('javabica.corporate_name')),
                'address'       => utf8_encode(config('javabica.address')),
                'phone'         => utf8_encode(config('javabica.phone')),
                'email'         => utf8_encode(config('javabica.email')),
                'website'       => utf8_encode(config('javabica.website')),
            ]);
            
            $customer = new Party([
                'name' => utf8_encode($dataCollection->billing_first_name . ' ' . $dataCollection->billing_last_name),
                'custom_fields' => [
                    'Alamat' => utf8_encode($dataCollection->billing_address . ', ' . $dataCollection->billing_city . ', ' . $dataCollection->billing_province . ', ' . $dataCollection->billing_postal_code . ', ' . $dataCollection->billing_country . '.'),
                    'Telp' => utf8_encode($dataCollection->contact_billing_phone),
                    // 'email' => utf8_encode($dataCollection->contact_email),
                ],
            ]);
            
            $items = [];
            
            foreach ($dataCollection->product_order as $cart) {
                $listCart = (new InvoiceItem())
                    ->title(utf8_encode($cart->product_name)) // Encode title jika diperlukan
                    ->description(utf8_encode($cart->variant_description)) // Encode deskripsi jika diperlukan
                    ->quantity($cart->qty);
                $listCart->product_code = utf8_encode($cart->sku); // Encode product_code jika diperlukan
                $listCart->note = utf8_encode($cart->note); // Encode note jika diperlukan
            
                if ($cart->discount_price != null || $cart->discount_price > 0) {
                    $listCart->sub_total_price = $cart->qty * $cart->discount_price;
                    $reformat = $cart->qty * ($cart->acctual_price - $cart->discount_price);
                    $listCart = $listCart->pricePerUnit($cart->acctual_price);
                    $listCart = $listCart->discount($reformat);
                } else {
                    $listCart = $listCart->pricePerUnit($cart->purchase_price);
                }
            
                array_push($items, $listCart);
            }
            
            $notes = [
                '',
                '',
                utf8_encode($dataCollection->invoice_note), // Encode invoice_note jika diperlukan
            ];
            
            $notes = implode("<br>", $notes);
            
            // dd($dataCollection, $customer, $items, $notes);
            $invoice = Invoice::make('Invoice')
                ->template('invoice')
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
        
                $invoice->shipping_amount =  $dataCollection->courier_cost;
                $invoice->companyDetail   =  $companyDetail;
                $invoice->voucher         =  $dataCollection->voucher;
            
            // Return PDF
            return $invoice->stream();
                
        } catch (\Exception $e) {
            // Tangani error, misalnya dengan mencetak pesan error
            echo "Terjadi kesalahan saat menampilkan invoice: " . $e->getMessage();
        }
    }
}
