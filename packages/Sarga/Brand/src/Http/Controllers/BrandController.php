<?php namespace Sarga\Brand\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Sarga\Brand\Repositories\BrandRepository;
use Sarga\Shop\Repositories\CategoryRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class BrandController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    protected $brandRepository;
    protected $sellerRepository;
    protected $categoryRepository;

    public function __construct(BrandRepository $brandRepository,
                                SellerRepository $sellerRepository,
                                CategoryRepository $categoryRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->sellerRepository = $sellerRepository;
        $this->categoryRepository = $categoryRepository;
        $this->_config = request('_config');
    }

    public function index() {
        return view($this->_config['view']);
    }

    public function create(){
        $sellers = $this->sellerRepository->findByField('is_approved',1);
        $categories = $this->categoryRepository->getCategoryTree();
        return view($this->_config['view'],compact('sellers','categories'));
    }

    public function store(){
        $this->validate(request(), [
            'code'       => ['required', 'unique:brands,code', new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
            'position' => 'numeric',
            'status' => 'numeric',
            'image.*'     => 'mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $this->brandRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Brand']));

        return redirect()->route($this->_config['redirect']);
    }

    public function edit($id){
        $brand = $this->brandRepository->find($id);
        $sellers = $this->sellerRepository->findByField('is_approved',1);
        $categories = $this->categoryRepository->getCategoryTree();
        return view($this->_config['view'], compact('brand','sellers','categories'));
    }

    public function update($id){
        $this->validate(request(), [
            'code'       => ['required', 'unique:brands,code,'. $id, new \Webkul\Core\Contracts\Validations\Code],
            'name' => 'required',
            'position' => 'numeric',
            'status' => 'numeric',
            'image.*'     => 'mimes:bmp,jpeg,jpg,png,webp',
        ]);

        $this->brandRepository->update(request()->all(), $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Brand']));

        return redirect()->route($this->_config['redirect']);
    }

    public function destroy($id){
        try {

            $this->brandRepository->delete($id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Brand']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Brand']));
        }
    }

    public function massDestroy(){

        $brandIds = explode(',', request()->input('indexes'));

        try {

            $this->brandRepository->destroy($brandIds);
            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Brand']));

        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Brand']));
        }
        return redirect()->route($this->_config['redirect']);
    }

}