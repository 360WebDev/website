<?php

namespace Tests\Feature\Roles;

use App\Model\Role;
use App\Repository\RoleRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleRepositoryTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @var RoleRepository
	 */
	private $roleRepository;

	public function setUp()
	{
		parent::setUp();
		$this->roleRepository = new RoleRepository(new Role);
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
		$roleMember = $this->roleRepository->getBySlug('foo');

		$this->assertEquals(0, Role::count());
		$this->assertNull($roleMember);
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
