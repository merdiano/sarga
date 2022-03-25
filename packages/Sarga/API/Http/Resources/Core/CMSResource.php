<?php

namespace Sarga\API\Http\Resources\Core;

class CMSResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request){
        return [
            'id'           => $this->id,
            'content'      => $this->content,
            'page_title'   => $this->page_title,
            'html_content' => $this->html_content,
        ];
    }
}