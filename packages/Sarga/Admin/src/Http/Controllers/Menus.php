<?php

namespace Sarga\Admin\Http\Controllers;
use Sarga\Admin\src\DataGrids\MenuDataGrid;
use Sarga\Shop\Repositories\MenuRepository;
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
        protected MenuRepository $attributeRepository
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

    public function create(){

    }

    public function store(){

    }

    public function edit(){

    }

    public function update(){}
}