<?php

namespace Sarga\Admin\Http;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Firebase
{
    public $data;

    public $notification;


    public function __construct(protected $to,$content,protected $priority = 'high')
    {
        $this->data = [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',

        ] + $content;

        $this->notification = [
            'sound' => 'default',
            'title' => $content['title'],
            'body' =>$content['content']
        ];

    }

    public function send(){
        $body = json_encode((object)$this);

        $response = Http::withHeaders([
            'Authorization' => config('push.push.token'),
            'Content-Type' => 'application/json'
        ])->withBody($body,'application/json')
            ->timeout(30)
            ->post(config('push.push.url'));


        if($response->failed())
        {
            Log::error($response);
            $response->throw();
        }
        Log::info($response->body());
    }
}