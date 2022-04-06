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
        $this->categoryRepository->getCategoryTreeWithoutDescendant(request()->input('parent_id'));
    }

}