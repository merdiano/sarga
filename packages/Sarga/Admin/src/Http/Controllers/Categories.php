<?php

namespace Sarga\Admin\Http\Controllers;

use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Http\Controllers\CategoryController;
use Webkul\Core\Repositories\ChannelRepository;

class Categories extends CategoryController
{
    public function __construct(ChannelRepository $channelRepository,
                                CategoryRepository $categoryRepository,
                                AttributeRepository $attributeRepository){
        $this->_config = request('_config');
        $this->channelRepository = $channelRepository;
        $this->categoryRepository = $categoryRepository;
        $this->attributeRepository = $attributeRepository;
    }
}