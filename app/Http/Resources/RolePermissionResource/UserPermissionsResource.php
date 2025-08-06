<?php

namespace App\Http\Resources\RolePermissionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPermissionsResource extends JsonResource
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
            'id'                       =>$this->resource['data']->permission->id,
            'name'                     => $this->resource['data']->permission->name,
            'guard_name'               => $this->resource['data']->permission->guard_name,
            'uspdated_at'               => $this->resource['data']->permission->updated_at,
            'created_at'               => $this->resource['data']->permission->created_at,
            
        ];
    }
}
