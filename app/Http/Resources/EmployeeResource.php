<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CompanyResource;

class EmployeeResource extends JsonResource
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
            'employee_id'   => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'created'   => $this->created_at->toDayDateTimeString(),
            'company' => $this->company_id ? new CompanyResource($this->company) : null,
            'author'    => $this->user_id ? new UserResource($this->author) : null,
        ];
    }
}
