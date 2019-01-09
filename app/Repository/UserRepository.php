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
	 * @return Collection
	 */
	public function findAllAdmin(): Collection
	{
		return $this->model->newQuery()->whereHas('role', function (Builder $query) {
			$query->where('slug', 'admin');
		})->get();
	}

	/**
	 * Specify model class name
	 *
	 * @return string
	 */
	public function model(): string
	{
		return User::class;
	}
}
