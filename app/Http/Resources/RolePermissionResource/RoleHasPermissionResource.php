<?php

namespace App\Http\Resources\RolePermissionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleHasPermissionResource extends JsonResource
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
            'id'                       =>$this->resource['data']->belogsTo_Permission->id,
            'name'                     => $this->resource['data']->belogsTo_Permission->name,
            'guard_name'               => $this->resource['data']->belogsTo_Permission->guard_name,
            'updated_at'               => $this->resource['data']->belogsTo_Permission->updated_at,
            'created_at'               => $this->resource['data']->belogsTo_Permission->created_at,
            
        ];
    }
}
