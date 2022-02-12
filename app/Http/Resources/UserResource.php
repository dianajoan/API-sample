<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user-id'    => $this->id,
            'name'      => $this->name,
            'email'      => $this->email,
            'is-admin'  => $this->is_admin == 1 ? true : false,
            'created'   => $this->created_at->toDayDateTimeString(),
            'companies' => $this->companies,
            'employees' => $this->employees,
        ];
    }
}
