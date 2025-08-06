<?php

namespace App\Http\Resources\HistoryVoucherResource;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryVoucherShowAllResource extends JsonResource
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
            'id'            => $this->resource['data']->id,
            'voucher_id'    => $this->resource['data']->voucher_id,
            'user_id'       => $this->resource['data']->user_id,
            'order_id'      => $this->resource['data']->order_id,
            'voucher'       => $this->resource['data']->voucher,
            'user'          => $this->resource['data']->user,
            'order'         => $this->resource['data']->order,
        ];
    }
}
