<?php

namespace App\Http\Resources\UserShippingAddress;

use Illuminate\Http\Resources\Json\JsonResource;

class ShippingAddressShowAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource['data']->fk_users) {

            $user = array(
                'id'                   => $this->resource['data']->fk_users->id,
                'name'                 => $this->resource['data']->fk_users->name,
                'uuid'                 => $this->resource['data']->fk_users->uuid,
            );
        } else {
            $user = null;
        }

        return [
            'id'                   => $this->resource['data']->id,
            'first_name'           =>  $this->resource['data']->first_name,
            'last_name'            =>  $this->resource['data']->last_name,
            'phone_number'         =>  $this->resource['data']->phone_number,
            'label_place'          => $this->resource['data']->label_place,
            'courier_note'         => $this->resource['data']->courier_note,
            'address'              => $this->resource['data']->address,
            'city'                 => [
                'id'       => $this->resource['data']->city,
                'label'    => $this->resource['data']->city_label,
            ],
            'province'             => [
                'id'       => $this->resource['data']->province,
                'label'    => $this->resource['data']->province_label,
            ],
            'user'                 => $user,
            'postal_code'          => $this->resource['data']->postal_code,
        ];
    }
}
