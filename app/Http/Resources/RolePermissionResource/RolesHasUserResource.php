<?php

namespace App\Http\Resources\RolePermissionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class RolesHasUserResource extends JsonResource
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
          
            'name'    => $this->resource['data'],
               
        ];
    }
}
