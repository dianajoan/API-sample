<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResourceCollection extends JsonResource
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
            'employee-id'    => $this->id,
            'first_name'      => $this->first_name,
            'last_name'      => $this->last_name,
            'company_id'      => $this->company_id,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'created'   => $this->created_at->toDayDateTimeString(),
        ];
    }
}
