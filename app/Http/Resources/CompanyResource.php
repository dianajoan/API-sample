<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EmployeeResourceCollection;
use App\Http\Resources\UserResource;

class CompanyResource extends JsonResource
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
            'company-id'=> $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'logo'      => asset('storage/public/'.$this->logo),
            'website'   => $this->website,
            'created'   => $this->created_at->toDayDateTimeString(),
            'author'    => $this->user_id ? new UserResource($this->author) : null,
            'employees' => $this->employees
        ];
    }
}
