<?php

namespace Sarga\Shop\Repositories;

use Sarga\Shop\Models\Channel;
use Webkul\Core\Repositories\ChannelRepository as BagistoChannelRepo;

class ChannelRepository extends BagistoChannelRepo
{
    function model()
    {
        return Channel::class;
    }
}