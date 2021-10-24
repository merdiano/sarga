<?php

namespace Sarga\Shop\Database\Seeders;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Webkul\Category\Repositories\CategoryRepository;

class DemoCategoryTableSeeder extends Seeder
{

    private $numberOfParentCategories = 10;

    private $numberOfChildCategories = 5;

    public function __construct(
        Faker              $faker,
        CategoryRepository $categoryRepository
    )
    {
        $this->faker = $faker;
        $this->categoryRepository = $categoryRepository;
    }

    public function run()
    {
        $this->categoryRepository->deleteWhere([['id', '!=', 1]]);
        for ($i = 2; $i < $this->numberOfParentCategories; $i++) {
            $createdCategory = $this->categoryRepository->create([
                'id' => $i,
                'slug' => $this->faker->slug,
                'name' => $this->faker->firstName,
                'description' => $this->faker->text(),
                'parent_id' => 1,
                'status' => 1,
            ]);

            if ($createdCategory) {
                for ($j = ($i-1)*$this->numberOfParentCategories; $j < ($i-1)*$this->numberOfParentCategories+$this->numberOfChildCategories; ++$j) {

                    $this->categoryRepository->create([
                        'id' => $j,
                        'slug' => $this->faker->slug,
                        'name' => $this->faker->firstName,
                        'description' => $this->faker->text(),
                        'parent_id' => $createdCategory->id,
                        'status' => 1
                    ]);
                }
            }
        }
    }
}
