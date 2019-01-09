<?php
namespace Tests\Feature\Repository\Stubs;

use App\Repository\Repository;

class DummyRepository extends Repository
{

	/**
	 * Specify model class name
	 *
	 * @return string
	 */
	public function model(): string
	{
		return FooModel::class;
	}
}
