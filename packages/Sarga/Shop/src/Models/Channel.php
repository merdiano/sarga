<?php namespace Sarga\Shop\Models;
use Webkul\Core\Models\Channel as BagistoChannel;
use Webkul\Core\Models\SliderProxy;

class Channel extends BagistoChannel
{
    public function sliders()
    {
        return $this->hasMany(SliderProxy::modelClass());
    }
}