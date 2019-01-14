<?php

namespace Tests\Feature\Roles;

use App\Model\Role;
use App\Repository\RoleRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleRepositoryTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @var RoleRepository
	 */
	private $roleRepository;

	/**
	 * @throws \App\Repository\RepositoryException
	 */
	public function setUp()
	{
		parent::setUp();
		$this->roleRepository = new RoleRepository($this->app);
	}

	public function tearDown()
	{
		parent::tearDown();
		$this->roleRepository = null;
	}

	/**
	 * @test
	 */
	public function get_member_role_if_roles_tables_is_empty()
	{
		$roleMember = $this->roleRepository->getBySlug('membre');

		$this->assertEquals(1, Role::count());
		$this->assertEquals('member', $roleMember->name);
	}

	/**
	 * @test
	 */
	public function get_role_is_not_exist()
	{
		$this->expectException(ModelNotFoundException::class);
		$this->roleRepository->getBySlug('foo');
	}

	/**
	 * @test
	 */
	public function get_member_role_if_roles_is_not_empty()
	{
		Role::create([
			'name' => 'member',
			'slug' => 'membre',
			'description' => 'aeazea'
		]);

		$roleMember = $this->roleRepository->getBySlug('membre');

		$this->assertEquals(1, Role::count());
		$this->assertEquals('member', $roleMember->name);
	}

}
