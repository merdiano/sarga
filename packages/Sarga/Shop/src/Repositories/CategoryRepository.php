<?php

namespace Sarga\Shop\Repositories;

use Webkul\Category\Repositories\CategoryRepository as WCategoryRepository;

class CategoryRepository extends WCategoryRepository
{

    public function getCategoryTree($id = null)
    {
        static $categories = [];

        if (array_key_exists($id, $categories)) {
            return $categories[$id];
        }

        return $categories[$id] = $id
            ? $this->model::orderBy('position', 'ASC')->descendantsAndSelf($id)->toTree($id)
            : $this->model::orderBy('position', 'ASC')->get()->toTree();
    }

    public function getInvisibleCategories(){
        return $this->getModel()
            ->whereNotNull('parent_id')
            ->where('status',0)
            ->get();
    }

    public function getDescriptionCategories(){
        return $this->getModel()
            ->whereNotNull('parent_id')
            ->where('status',1)
            ->where('display_mode','description_only')
            ->get();
    }
}