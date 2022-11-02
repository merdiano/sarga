<?php namespace Sarga\API\Http\Controllers;

use Webkul\API\Http\Controllers\Shop\Controller;
use Sarga\API\Http\Resources\Core\Channel;
use Sarga\Shop\Repositories\ChannelRepository;

class Channels extends Controller
{

    public function __construct(protected ChannelRepository $channelRepository)
    {
    }

    public function index()
    {
        return Channel::make($this->channelRepository->first());

    }

    public function get($channel_id){
        $this->categoryRepository->getCategoryTreeWithoutDescendant(request()->input('parent_id'));
    }

}