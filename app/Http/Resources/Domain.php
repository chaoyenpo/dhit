<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Domain extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tag' => $this->tag,
            'domain_expired_at' => $this->domain_expired_at->toDateString(),
            'dns' => $this->dns,
            'nameservers' => $this->nameservers,
            'vendor' => $this->vendor,
            'vendor_account' => $this->vendor_account,
            'remark' => $this->remark,
            'use' => $this->use,
        ];
    }
}
