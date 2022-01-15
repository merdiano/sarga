<?php

namespace Sarga\API\Http\Controllers;

use App\Http\Controllers\Controller;
use Sarga\API\Http\Resources\Catalog\Category;
use Sarga\Shop\Repositories\CategoryRepository;


class Categories extends Controller
{
    /**
     * CategoryRepository object
     *
     * @var \Sarga\Shop\Repositories\CategoryRepository
     */
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function index()
    {
        return Category::collection(
            $this->categoryRepository->getVisibleCategoryTree(request()->input('parent_id'))
        );
    }

    public function details($id){
        $children = $this->categoryRepository->findWhere(['parent_id' => $id, 'status'=>1])
            ->orderBy('position', 'ASC');

    }

}