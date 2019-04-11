<?php

use App\Model\Category;
use Illuminate\Database\Seeder;

/**
 * Class CategoryTableSeeder
 */
class CategoryTableSeeder extends Seeder
{

    const MAX_CREATED = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        factory(Category::class, static::MAX_CREATED)->create();
    }
}
