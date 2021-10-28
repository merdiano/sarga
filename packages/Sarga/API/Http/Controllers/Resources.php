<?php

namespace Sarga\API\Http\Controllers;

use Webkul\API\Http\Controllers\Shop\ResourceController;

class Resources extends ResourceController
{
    public function get($code){
        $query = isset($this->_config['authorization_required']) && $this->_config['authorization_required'] ?
            $this->repository->where(['customer_id'=>auth()->user()->id,'code'=>$code])->first() :
            $this->repository->where('code',$code)->first();

        if($query)
            return new $this->_config['resource']($query);
        else
            return response()->json(['status'=>false,'message'=>'Not found'],404);
    }
}