<?php namespace Sarga\Brand\Repositories;

use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;

class BrandRepository extends Repository
{

    public function model()
    {
        return 'Sarga\Brand\Contracts\Brand';
    }

    public function create(array $data){
        $brand = $this->model->create($data);

        if(isset($data['sellers'])){
            $brand->sellers()->sync($data['sellers']);
        }

        if(isset($data['categories'])){
            $brand->categories()->sync($data['categories']);
        }

        $this->uploadImages($brand,$data);

        return $brand;
    }

    public function update(array $data, $id){

        $brand = $this->find($id);

        $brand->update($data);

        $this->uploadImages($brand, $data);

        if(isset($data['sellers'])){
            $brand->sellers()->sync($data['sellers']);
        }

        if(isset($data['categories'])){
            $brand->categories()->sync($data['categories']);
        }

        return $brand;

    }

    public function uploadImages($brand,$data,$type = 'image') {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'brand/' . $brand->id;

                if ($request->hasFile($file)) {
                    if ($brand->{$type}) {
                        Storage::delete($brand->{$type});
                    }

                    $brand->{$type} = $request->file($file)->store($dir);
                    $brand->save();
                }
            }
        } else {
            if ($brand->{$type}) {
                Storage::delete($brand->{$type});
            }

            $brand->{$type} = null;
            $brand->save();
        }
    }

    public function actives(){
        return $this->findByField('status',1);
    }

    public function findAllBySeller($seller_id){
        $query = $this->leftJoin('seller_brands as sb','sb.brand_id','=','brands.id')
            ->where('sb.seller_id',$seller_id);

        if(request()->has('category_id')){
            $query->leftJoin('category_brands as cb','cb.brand_id','=','brands.id')
                ->where('cb.category_id',request()->get('category_id'));
        }

        $limit = request()->get('limit') ?? 10;
        $page = request()->get('page') ?? 1;

        return $query->orderBy('position', 'ASC')
            ->skip(($page-1) * $limit)
            ->take($limit)
            ->get();
    }
}