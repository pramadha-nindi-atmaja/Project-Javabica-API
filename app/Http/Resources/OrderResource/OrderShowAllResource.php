<?php

namespace App\Http\Resources\OrderResource;

use App\Http\Resources\OrderProductResource\OrderProductShowAllResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShowAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource['data']->fk_user) {

            $user = array(
                'id'                   => $this->resource['data']->fk_user->id,
                'name'                 => $this->resource['data']->fk_user->name,
                'uuid'                 => $this->resource['data']->fk_user->uuid,
            );
        } else {
            $user = null;
        }

    
        return [
            'id'                           => $this->resource['data']->id,
            'uuid'                         => $this->resource['data']->uuid,
            'order_number'                 => $this->resource['data']->order_number,
             
            'contact_email'                => $this->resource['data']->contact_email,
           
            'contact_phone'                => $this->resource['data']->contact_phone,
            'shipping_country'             => $this->resource['data']->shipping_country,
            'shipping_first_name'          => $this->resource['data']->shipping_first_name,
            'shipping_last_name'           => $this->resource['data']->shipping_last_name,
            'shipping_address'             => $this->resource['data']->shipping_address,
            'shipping_city'                => $this->resource['data']->shipping_city,
            'shipping_province'            => $this->resource['data']->shipping_province,
            'shipping_postal_code'         => $this->resource['data']->shipping_postal_code,
            'shipping_label_place'         => $this->resource['data']->shipping_label_place,
            'shipping_note_address'        => $this->resource['data']->shipping_note_address,

            'contact_billing_phone'       => $this->resource['data']->contact_billing_phone,
            'billing_country'             => $this->resource['data']->billing_country,
            'billing_first_name'          => $this->resource['data']->billing_first_name,
            'billing_last_name'           => $this->resource['data']->billing_last_name,
            'billing_address'             => $this->resource['data']->billing_address,
            'billing_city'                => $this->resource['data']->billing_city,
            'billing_province'            => $this->resource['data']->billing_province,
            'billing_postal_code'         => $this->resource['data']->billing_postal_code,
            'billing_label_place'         => $this->resource['data']->billing_label_place,
            'billing_note_address'        => $this->resource['data']->billing_note_address,

            'courier_agent'                => $this->resource['data']->courier_agent,
            'courier_agent_service'        => $this->resource['data']->courier_agent_service,
            'courier_agent_service_desc'   => $this->resource['data']->courier_agent_service_desc,
            'courier_estimate_delivered'   => $this->resource['data']->courier_estimate_delivered,
            'courier_resi_number'          => $this->resource['data']->courier_resi_number,
            'courier_cost'                 => $this->resource['data']->courier_cost,

            'payment_method'               => $this->resource['data']->payment_method,
            'payment_refrence_code'        => $this->resource['data']->payment_refrence_code,
            'payment_snap_token'           => $this->resource['data']->payment_snap_token,

            'invoice_note'                 => $this->resource['data']->invoice_note,
            'delivery_order_note'          => $this->resource['data']->delivery_order_note,

            'user'                         => $user,
            'users_profile'                => $user,
            'payment_status'               => $this->resource['data']->payment_status,
            'status'                       => $this->resource['data']->status,
            'created_at'                   => $this->resource['data']->created_at,
        ];
    }
}
