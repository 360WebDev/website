<?php

namespace Tests\Feature\Repository;

use App\Repository\RepositoryException;
use Tests\Feature\Repository\Stubs\DummyRepository;
use Tests\Feature\Repository\Stubs\FailRepository;
use Tests\Feature\Repository\Stubs\FooModel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function init_model()
	{
		$repository = new DummyRepository($this->app);

		$this->assertInstanceOf(FooModel::class, $repository->getModel());
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function init_model_who_not_model_instance()
	{
		$this->expectException(RepositoryException::class);
		new FailRepository($this->app);
	}
}
