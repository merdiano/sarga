<?php namespace Sarga\Brand\Http\Controllers;

use Sarga\Brand\Repositories\BrandRepository;

class BrandController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    protected $brandRepository;
    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->_config = request('_config');
    }

    public function index() {
        return view($this->_config['view']);
    }

    public function create(){
        return view($this->_config['view']);
    }

    public function edit($id){
        $brand = $this->brandRepository->find($id);
        return view($this->_config['view'], compact('brand'));
    }

    public function update(){

    }

    public function destroy($id){

    }

    public function massDestroy(){

    }

    public function productCount(){

    }

}