<?php

namespace Sarga\API\Http\Controllers;

use Sarga\API\Http\Resources\Catalog\Category;
use Webkul\API\Http\Controllers\Shop\CategoryController;

class Categories extends CategoryController
{
    public function index()
    {
        return Category::collection(
            $this->categoryRepository->getVisibleCategoryTree(request()->input('parent_id'))
        );
    }

}