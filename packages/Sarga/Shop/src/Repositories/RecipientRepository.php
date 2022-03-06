<?php

namespace Sarga\Shop\Repositories;

use Webkul\Core\Eloquent\Repository;

class RecipientRepository extends Repository
{

    public function model()
    {
        return 'Sarga\Shop\Contracts\Recipient';
    }
}