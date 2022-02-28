<?php
/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 9/22/2021
 * Time: 18:36
 */

namespace Sarga\Payment\Http\Resoures\Payment;


use Illuminate\Http\Resources\Json\JsonResource;

class Cusromer extends JsonResource
{
    public function toArray($request)
    {
        return [
            "Name" => $this->name,
            "Language" => "en-US",
            "Email" => $this->phone.'@ozan.com.tm',
            "HomePhone" => [
                "cc" => "993",
                "subscriber" => $this->phone
            ],
            "MobilePhone" => [
                "cc" => "993",
                "subscriber" => $this->phone
            ],
            "WorkPhone" => null
        ];
    }
}