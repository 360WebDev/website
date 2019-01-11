<?php

namespace Tests\Feature\Repository;

use App\Repository\RepositoryException;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Tests\Feature\Repository\Stubs\Bar;
use Tests\Feature\Repository\Stubs\DummyRepository;
use Tests\Feature\Repository\Stubs\FailRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{
	use RefreshDatabase;

	public function setUp()
	{
		parent::setUp();
		/** @var $db Connection */
		$db = $this->app['db']->connection();
		$db->getSchemaBuilder()->create('bars', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('slug')->nullable();
		});
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function init_model()
	{
		$repository = new DummyRepository($this->app);

		$this->assertInstanceOf(Bar::class, $repository->getModel());
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

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function it_be_find_model()
	{
		$bar1 = $this->createBar('foo');
		$bar2 = $this->createBar('bar');
		$repository = new DummyRepository($this->app);

		$this->assertEquals('foo', $repository->find($bar1->id)->name);
		$this->assertEquals('bar', $repository->find($bar2->id)->name);
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function it_be_find_by_slug()
	{
		$bar1 = $this->createBar('foo', 'foo');
		$repository = new DummyRepository($this->app);

		$this->assertEquals('foo', $repository->getBySlug('foo')->name);
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function it_be_find_model_does_not_exist()
	{
		$repository = new DummyRepository($this->app);

		$this->expectException(ModelNotFoundException::class);
		$this->assertEquals('foo', $repository->find(15)->first()->name);
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function it_be_save_model()
	{
		$repository = new DummyRepository($this->app);
		$data = [
			['name' => 'foo'],
			['name' => 'bar'],
			['name' => 'foobar'],
		];
		foreach ($data as $value) {
			$repository->save($value);
		}

		$this->assertEquals(3, (new Bar)->newQuery()->count());
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function it_be_update_model()
	{
		$bar1 = $this->createBar('foo');
		$repository = new DummyRepository($this->app);

		$attributes = ['name' => 'bar'];
		$repository->update($attributes, $bar1->id);

		$this->assertEquals('bar', $bar1->newQuery()->first()->name);
	}

	/**
	 * @param string $name
	 * @return Bar|Model
	 */
	private function createBar(string $name, ?string $slug = null): Model
	{
		return (new Bar)->newQuery()->create(compact('name', 'slug'));
	}
}
