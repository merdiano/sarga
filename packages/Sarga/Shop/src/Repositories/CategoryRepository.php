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
    /**
     * get visible category tree.
     *
     * @param  int  $id
     * @return \Illuminate\Support\Collection
     */
    public function getVisibleCategoryTree($id = null)
    {
        static $categories = [];

        if (array_key_exists($id, $categories)) {
            return $categories[$id];
        }

        $query = $this->model::orderBy('position', 'ASC')->where('status', 1);

        if(request()->has('vendor')){
            $query->whereHas('vendors', function($q){
               $q->where('id',request()->get('vendor'));
            });
        }

        return $categories[$id] = $id
            ? $query->descendantsAndSelf($id)->toTree($id)
            : $query->get()->toTree();
    }

    public function getCategoryTree($id = null)
    {
        static $categories = [];

        if (array_key_exists($id, $categories)) {
            return $categories[$id];
        }

        $query = $this->model::orderBy('position', 'ASC');

        if(request()->has('vendor')){
            $query->whereHas('vendors', function($q){
                $q->where('id',request()->get('vendor'));
            });
        }

        return $categories[$id] = $id
            ? $query->descendantsAndSelf($id)->toTree($id)
            : $query->get()->toTree();
    }

    public function getInvisibleCategories(){
        return $this->getModel()
            ->whereNotNull('parent_id')
            ->where('status',0)
            ->get();
    }

    public function getDescriptionCategories(){
        $query = $this->getModel()
            ->whereNotNull('parent_id')
            ->where('status',1)
            ->where('display_mode','description_only');

        if(request()->has('vendor')){
            $query->whereHas('vendors', function($q){
                $q->where('id',request()->get('vendor'));
            });
        }

        return $query->get();
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

        return $category;
    }

    public function update(array $data, $id, $attribute = 'id'){
        $category = parent::update($data,$id,$attribute );

        if (isset($data['vendors'])) {
            $category->vendors()->sync($data['vendors']);
        }

        return $category;
    }
}