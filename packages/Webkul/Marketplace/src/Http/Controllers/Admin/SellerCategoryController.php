<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\SellerCategoryRepository;

class SellerCategoryController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * SellerCategoryRepository object
     *
     * @var object
     */
    protected $sellerCategoryRepository;


    /**
     * SellerRepository object
     *
     * @var object
     */
    protected $sellerRepository;



    /**
     * CategoryRepository object
     *
     * @var object
     */
    protected $categoryRepository;

       /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\ReviewRepository $reviewRepository
     * @return void
     */
    public function __construct(
        SellerRepository $sellerRepository,
        CategoryRepository $categoryRepository,
        SellerCategoryRepository $sellerCategoryRepository
    )
    {
        $this->_config = request('_config');

        $this->sellerRepository = $sellerRepository;

        $this->categoryRepository = $categoryRepository;

        $this->sellerCategoryRepository = $sellerCategoryRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sellers = $this->sellerRepository->all();

        $categories = $this->categoryRepository->getCategoryTree();

        return view($this->_config['view'], compact('sellers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->all();

        $data['categories'] = json_encode($data['categories']);

        $this->sellerCategoryRepository->create($data);

        session()->flash('success', __('marketplace::app.admin.sellers.category.save-success.'));

        return redirect()->route('admin.marketplace.seller.category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $sellerCategories = $this->sellerCategoryRepository->find($id);

        $sellers = $this->sellerRepository->all();

        $categories = $this->categoryRepository->getCategoryTree();

        return view($this->_config['view'], compact('sellers', 'categories', 'sellerCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sellerCategories = $this->sellerCategoryRepository->find($id);

        $sellerCategories->update(request()->all());

        session()->flash('success', __('marketplace::app.admin.sellers.category.update-success'));

        return redirect()->route('admin.marketplace.seller.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sellerCategories = $this->sellerCategoryRepository->find($id);

        $sellerCategories->delete();

        session()->flash('success', __('marketplace::app.admin.sellers.category.delete-success'));

        return redirect()->route('admin.marketplace.seller.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $indexs = explode(',', request()->input('indexes'));

        foreach($indexs as $id) {

            $sellerCategories = $this->sellerCategoryRepository->find($id);

            $sellerCategories->delete();
        }


        session()->flash('success', __('marketplace::app.admin.sellers.category.delete-success'));

        return redirect()->route('admin.marketplace.seller.category.index');
    }

}
