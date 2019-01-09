<?php
namespace App\Repository;

use App\Model\Category;

/**
 * App CategoryRepository
 */
class CategoryRepository extends Repository
{

	/**
	 * Specify model class name
	 *
	 * @return string
	 */
	public function model(): string
	{
		return Category::class;
	}
}
