<?php
namespace App\Repository;

use App\Model\Role;

/**
 * Role repository
 */
class RoleRepository extends Repository
{
	const DEFAULT_ROLE = 'membre';

	/**
	 * RoleRepository constructor
	 *
	 * @param Role $role
	 */
	public function __construct(Role $role)
	{
		$this->model = $role;
	}

	/**
	 * @param string $slug
	 * @return \Illuminate\Database\Eloquent\Collection|Model
	 */
	public function getBySlug(string $slug)
	{
		return $this->model->newQuery()->where('slug', $slug)->firstOrCreate([
			'name'        => 'member',
			'slug'        => self::DEFAULT_ROLE,
			'description' => 'The default user role'
		]);
	}

}