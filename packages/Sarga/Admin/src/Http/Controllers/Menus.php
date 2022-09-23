<?php

namespace Sarga\Admin\Http\Controllers;
use Sarga\Admin\DataGrids\MenuDataGrid;
use Sarga\Admin\Http\Requests\MenuRequest;
use Sarga\Shop\Repositories\CategoryRepository;
use Sarga\Shop\Repositories\MenuRepository;
use Sarga\Brand\Repositories\BrandRepository;
use Webkul\Admin\Http\Controllers\Controller;

class Menus extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    public function __construct(
        protected MenuRepository $mRepository
    )
    {
        $this->_config = request('_config');
    }

    public function index(){
        if (request()->ajax()) {
            return app(MenuDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    public function create(CategoryRepository $repository){
        $categories = $repository->getCategoryTree(null, ['id']);

        return view($this->_config['view'], compact('categories'));
    }

    public function store(MenuRequest $request){

        $category = $this->mRepository->create($request->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Menu']));

        return redirect()->route($this->_config['redirect']);
    }

    public function edit(CategoryRepository $repository,$id){
        $menu = $this->mRepository->findOrFail($id);
        $categories = $repository->getCategoryTree(null, ['id']);
        return view($this->_config['view'], compact('menu','categories'));
    }

    public function update(MenuRequest $request,$id){
        $menu = $this->mRepository->update($request->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Menu']));

        return redirect()->route($this->_config['redirect']);
    }

    public function brands(BrandRepository $repository){
        if (request()->ajax()) {
            $results = [];

            foreach ($repository->search(request()->input('query')) as $row) {
                $results[] = [
                    'id'   => $row->id,
                    'name' => $row->name,
                ];
            }

            return response()->json($results);
        } else {
            return view($this->_config['view']);
        }
    }
}