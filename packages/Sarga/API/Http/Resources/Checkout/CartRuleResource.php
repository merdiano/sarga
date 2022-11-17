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
        $is_fixed = str_contains($this->action_type,'fixed');
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'is_fixed'    => $is_fixed,
            'is_discount' => $this->discount_amoun >0,
            'amount'      => (double) ($is_fixed ?
                core()->convertPrice($this->discount_amount,  $this->currency):
                $this->discount_amount)
        ];
    }

}