<?php
/**
 * Created by PhpStorm.
 * User: merdan
 * Date: 9/22/2021
 * Time: 18:50
 */

namespace Sarga\Payment\Http\Resoures\Payment;


use Illuminate\Http\Resources\Json\JsonResource;

class Browser extends JsonResource
{
    public function toArray($request)
    {
        return [
            "AcceptHeader" => "*/*",
            "IpAddress" => "10.33.27.3",
            "Language" => "ru-RU",
            "ScreenColorDepth" => 48,
            "ScreenHeight" => 1200,
            "ScreenWidth" => 1900,
            "UserAgentString" => "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.3"
        ];
    }

}