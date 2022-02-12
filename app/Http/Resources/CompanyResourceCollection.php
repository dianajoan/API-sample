<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResourceCollection extends JsonResource
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
            'company-id'    => $this->id,
            'name'      => $this->name,
            'email'      => $this->email,
            'logo'      => $this->logo,
            'website'      => $this->website,
            'created'   => $this->created_at->toDayDateTimeString(),
        ];
    }
}
