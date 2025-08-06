<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{


    public function getSnapToken($payload)
    {

        $params = [
            'transaction_details' => [
                'order_id'       => $payload['transaction_details']['order_id'],
                'gross_amount'   => $payload['transaction_details']['gross_amount'],
            ],

            'item_details' =>  $payload['cart'],

            'customer_details' => [
                'first_name' => $payload['customer_details']['first_name'],
                'email'      => $payload['customer_details']['email'],
                'phone'      =>  $payload['customer_details']['phone'],
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}
