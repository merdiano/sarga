<?php

namespace Sarga\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;

class CartRuleResource extends JsonResource
{
    public function __construct($resource,$currency){
        parent::__construct($resource);
        $this->currency = $currency;
    }

    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'action_type' => $this->action_type,
            'amount'      => str_contains($this->action_type,'fixed')? core()->formatPrice($this->discount_amount,  $this->currency):$this->discount_amount.'%'
        ];
    }

}