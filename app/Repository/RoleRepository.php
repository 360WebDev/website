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
     * Retreives the role by slug, if not slug in the bdd => create default role who is member
     *
     * @param string $slug
     * @return Role|null
     */
    public function getBySlug(string $slug): ?Role
    {
        if ($this->model->newQuery()->get()->count() === 0 &&
            $slug === self::DEFAULT_ROLE
        ) {
            $this->model->newQuery()->create([
                'name'        => 'member',
                'slug'        => self::DEFAULT_ROLE,
                'description' => 'The default user role'
            ]);
        }
        return $this->model->newQuery()->where('slug', $slug)->first();
    }

	/**
	 * Specify model class name
	 *
	 * @return string
	 */
	public function model(): string
	{
		return Role::class;
	}
}
