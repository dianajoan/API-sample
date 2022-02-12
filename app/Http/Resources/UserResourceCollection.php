<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'user-id'    => $this->id,
            'name'      => $this->name,
            'email'      => $this->email,
            'is-admin'  => $this->is_admin == 1 ? true : false,
            'created'   => $this->created_at->toDayDateTimeString(),
        ];
    }
}
