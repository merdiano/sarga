<?php

namespace Sarga\API\Http\Resources\Checkout;

class PickupAddress extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'contact_number' => $this->contact_number,
            'state' => $this->state,
            'city' => $this->city,
            'street' => $this->street,
        ];
    }
}