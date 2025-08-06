<?php

namespace App\Http\Resources\UsersResources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserShowAllWebResource extends JsonResource
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
            'id'             => $this->resource['data']->id,
            'uuid'           => $this->resource['data']->uuid,
            'name'           => $this->resource['data']->name,
            
            'last_name'           => $this->resource['data']->last_name,
            'phone'             => $this->resource['data']->phone,
            'dob'           => $this->resource['data']->dob,
            'gender'           => $this->resource['data']->gender,
            'avatar'           => $this->resource['data']->avatar,

            'email'           =>  $this->resource['data']->email,
            'created_at'      =>  $this->resource['data']->created_at,
            'updated_at'      =>  $this->resource['data']->updated_at,
            'verified_at'     => $this->resource['data']->email_verified_at,
            'roles'           => $this->resource['data']->roles
        ];
    }
}
