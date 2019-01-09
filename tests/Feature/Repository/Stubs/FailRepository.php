<?php
namespace Tests\Feature\Repository\Stubs;

use App\Repository\Repository;

class FailRepository extends Repository
{

	/**
	 * Specify model class name
	 *
	 * @return string
	 */
	public function model(): string
	{
		return FailModel::class;
	}
}
