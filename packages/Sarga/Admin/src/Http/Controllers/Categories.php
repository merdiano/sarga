<?php

namespace Sarga\Admin\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Category\Http\Controllers\CategoryController;
use Webkul\Category\Http\Requests\CategoryRequest;
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Webkul\Category\Http\Requests\CategoryRequest  $categoryRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $categoryRequest)
    {
        Event::dispatch('catalog.category.create.before');

        $category = $this->categoryRepository->create($categoryRequest->all());

//        Event::dispatch('catalog.category.create.after', $category);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Category']));

        return redirect()->route($this->_config['redirect']);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Category\Http\Requests\CategoryRequest  $categoryRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $categoryRequest, $id)
    {
        Event::dispatch('catalog.category.update.before', $id);

        $category = $this->categoryRepository->update($categoryRequest->all(), $id);

//        Event::dispatch('catalog.category.update.after', $category);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Category']));

        return redirect()->route($this->_config['redirect']);
    }
}