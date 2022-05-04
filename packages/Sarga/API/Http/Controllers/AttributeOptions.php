<?php

namespace Sarga\API\Http\Controllers;

use Illuminate\Http\Request;
use Sarga\API\Http\Resources\Catalog\AttributeOption;
use Sarga\Shop\Repositories\AttributeOptionRepository;

class AttributeOptions extends \Webkul\RestApi\Http\Controllers\V1\Shop\ResourceController
{
    protected $requestException = ['page', 'limit', 'pagination', 'sort', 'order', 'token','locale','search'];
    /**
     * Repository class name.
     *
     * @return string
     */
    public function repository()
    {
        return AttributeOptionRepository::class;
    }
    /**
     * Resource class name.
     *
     * @return string
     */
    public function resource()
    {
        return AttributeOption::class;
    }

    /**
     * Is resource authorized.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        return false;
    }

    public function allResources(Request $request)
    {
        $query = $this->getRepositoryInstance()->scopeQuery(function ($query) use ($request) {

            foreach ($request->except($this->requestException) as $input => $value) {
                $query = $query->whereIn($input, array_map('trim', explode(',', $value)));
            }

            if($key = $request->input('search')){
                $query = $query->where('admin_name','like', '%'.$key.'%');
                //todo search in translations
            }

            if ($sort = $request->input('sort')) {
                $query = $query->orderBy($sort, $request->input('order') ?? 'desc');
            } else {
                $query = $query->orderBy('id', 'desc');
            }

            return $query;
        });

        if (is_null($request->input('pagination')) || $request->input('pagination')) {
            $results = $query->paginate($request->input('limit') ?? 10);
        } else {
            $results = $query->get();
        }

        return $this->getResourceCollection($results);
    }
}