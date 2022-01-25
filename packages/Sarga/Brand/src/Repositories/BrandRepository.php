<?php namespace Sarga\Brand\Repositories;

use Webkul\Core\Eloquent\Repository;

class BrandRepository extends Repository
{

    public function model()
    {
        return 'Sarga\Brand\Contracts\Brand';
    }
}