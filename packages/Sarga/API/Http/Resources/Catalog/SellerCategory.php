<?php
/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 12/29/2021
 * Time: 7:30
 */

namespace Sarga\API\Http\Resources\Catalog;


use Illuminate\Http\Resources\Json\JsonResource;

class SellerCategory  extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => $this->type,
            'ids' => json_decode($this->categories)
        ];
    }
}