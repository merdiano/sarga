<?php

namespace Sarga\API\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'id'           => $this->id,
            'note'         => $this->company_name,
            'address1'     => explode(PHP_EOL, $this->address1),
            'state'        => $this->state,
            'city'         => $this->city,
        ];
    }
}