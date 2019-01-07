<?php

namespace App\Repository;

use App\Model\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * App UserRepository
 */
class UserRepository extends Repository
{

    /**
     * @var User
     */
    protected $model;

    /**
     * UserRepository constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

	/**
	 * @return Collection
	 */
	public function findAllAdmin(): Collection
	{
		return $this->model->newQuery()->whereHas('role', function (Builder $query) {
			$query->where('slug', 'admin');
		})->get();
	}

}
