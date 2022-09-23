<?php

namespace Sarga\Shop\Repositories;
use Webkul\Core\Eloquent\Repository;
class MenuRepository extends Repository
{
    public function model(): string
    {
        return 'Sarga\Shop\Contracts\Menu';
    }

    /**
     * Create Menu.
     *
     * @param  array  $data
     * @return \Webkul\Category\Contracts\Category
     */
    public function create(array $data)
    {
        if (
            isset($data['locale'])
            && $data['locale'] == 'all'
        ) {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];

                        $data[$locale->code]['locale_id'] = $locale->id;
                    }
                }
            }
        }

        $menu = $this->model->create($data);

//        $this->uploadImages($data, $menu);
//        $product->categories()->sync($data['categories']);
        if (isset($data['categories'])) {
            $menu->categories()->sync($data['categories']);
        }

        if (isset($data['brands'])) {
            $menu->brands()->sync($data['brands']);
        }

        return $menu;
    }

    public function update(array $data, $id)
    {
        $menu = $this->find($id);
    }

    /**
     * Set same value to all locales in category.
     *
     * To Do: Move column from the `category_translations` to `category` table. And remove
     * this created method.
     *
     * @param  array  $data
     * @param  string $attributeNames
     * @return array
     */
    private function setSameAttributeValueToAllLocale(array $data, ...$attributeNames)
    {
        $requestedLocale = core()->getRequestedLocaleCode();

        $model = app()->make($this->model());

        foreach ($attributeNames as $attributeName) {
            foreach (core()->getAllLocales() as $locale) {
                if ($requestedLocale == $locale->code) {
                    foreach ($model->translatedAttributes as $attribute) {
                        if ($attribute === $attributeName) {
                            $data[$locale->code][$attribute] = isset($data[$requestedLocale][$attribute])
                                ? $data[$requestedLocale][$attribute]
                                : $data[$data['locale']][$attribute];
                        }
                    }
                }
            }
        }

        return $data;
    }
}