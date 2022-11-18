<?php

namespace Sarga\Shop\Repositories;

use Illuminate\Support\Facades\DB;
use Sarga\Shop\Models\Category;
use Webkul\Category\Models\CategoryTranslationProxy;
use Webkul\Category\Repositories\CategoryRepository as WCategoryRepository;

class CategoryRepository extends WCategoryRepository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model(): string
    {
        return \Sarga\Shop\Contracts\Category::class;
    }

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

    public function findByName($name, $limit = 10 ){
        return CategoryTranslationProxy::modelClass()::where('name', 'like', '%'.$name.'%')
            ->distinct()
            ->limit($limit)
            ->groupBy('category_id')
            ->get();
    }

    public function create(array $data){
        $category = parent::create($data);

        if (isset($data['vendors'])) {
            $category->vendors()->sync($data['vendors']);
        }

    }

    public function update(array $data, $id, $attribute = 'id'){
        $category = parent::update($data,$id,$attribute );
        if (isset($data['vendors'])) {
            $category->vendors()->sync($data['vendors']);
        }
    }
}