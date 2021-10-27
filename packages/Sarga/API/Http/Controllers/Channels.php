<?php namespace Sarga\API\Http\Controllers;

use Webkul\API\Http\Controllers\Shop\Controller;
use Sarga\API\Http\Resources\Core\Channel;
use Sarga\Shop\Repositories\ChannelRepository;

class Channels extends Controller
{
    protected $channelRepository;

    public function __construct(ChannelRepository $channelRepository)
    {
        $this->channelRepository = $channelRepository;

    }

    public function index()
    {

        $channels = $this->channelRepository->with('sliders')->get();

        if($channels)
        {
            return Channel::collection($channels);
        }
        else
        {
            return response()->json(['not found'],404);
        }
    }

    public function get($channel_id){
        $this->categoryRepository->getVisibleCategoryTree(request()->input('parent_id'));
    }

}