<?php

namespace Sarga\Shop\Repositories;

use Webkul\Attribute\Repositories\AttributeOptionRepository as WAttributeOptionRepository;

class AttributeOptionRepository extends WAttributeOptionRepository
{

    public function getOptionLabel($option_id){
        static $options = [];

        if (!array_key_exists($option_id, $options)) {
            $options[$option_id] = $this->find($option_id);
        }

        return $options[$option_id]->label ?? $options[$option_id]->admin_name;
    }
}