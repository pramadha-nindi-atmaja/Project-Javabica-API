<?php

namespace App\Http\Resources\RolePermissionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionsResource extends JsonResource
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
            'id'                       =>$this->resource['data']->id,
            'name'                     => $this->resource['data']->name,
            'guard_name'               => $this->resource['data']->guard_name,
            'uspdated_at'               => $this->resource['data']->updated_at,
            'created_at'               => $this->resource['data']->created_at,
            
        ];
    }
}
