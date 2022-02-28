<?php
/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 9/22/2021
 * Time: 18:33
 */

namespace Sarga\Http\Resoures\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class Card extends JsonResource
{
    public function toArray($request)
    {
        return [
            "PAN" => "6015840000000843",
            "ExpiryDate" => "2401",
            "SecurityCode2" => "725",
            "Name" => "John Doe",
            "TAVV" => null,
            "IsCardOnFile" => false
        ];
    }
}