<?php

namespace App\Http\Resources\MachineResource;

use Illuminate\Http\Resources\Json\JsonResource;

class MachineShowAllResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->resource['data']->id,
            'user_id'          => $this->resource['data']->user_id,
            'username'         => $this->resource['data']->user->name,
            'product_id'       => $this->resource['data']->product_id,
            'product_name'     => $this->resource['data']->product->name,
            'serial_number'    => $this->resource['data']->serial_number,
            'purchase_date'    => $this->resource['data']->purchase_date,
            'description'      => $this->resource['data']->description,
            'product'          => $this->resource['data']->product,
            'image'            => $this->resource['data']->product->hasMany_image,
            'user'             => $this->resource['data']->user,
        ];
    }
}
