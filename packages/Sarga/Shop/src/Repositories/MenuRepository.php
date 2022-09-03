<?php

namespace Sarga\Shop\Repositories;
use Webkul\Core\Eloquent\Repository;
class MenuRepository extends Repository
{
    public function model(): string
    {
        return 'Sarga\Shop\Contracts\Menu';
    }
}