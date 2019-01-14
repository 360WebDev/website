<?php
declare(strict_types=1);

namespace App\Repository;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

/**
 * App base Repository
 */
abstract class Repository
{

    /**
     * @var Model
     */
    protected $model;

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * Repository constructor
	 *
	 * @param Application $application
	 * @throws RepositoryException
	 */
	public function __construct(Application $application)
	{
		$this->application = $application;
		$this->makeModel();
	}

	/**
	 * @throws RepositoryException
	 */
	public function resetModel()
	{
		$this->makeModel();
	}

	/**
	 * Specify model class name
	 *
	 * @return string
	 */
	abstract public function model(): string;

	/**
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Collection|Model
     */
    public function getBySlug(string $slug)
    {
        return $this->model->newQuery()->where('slug', $slug)->firstOrFail();
    }

	/**
	 * @param int   $id
	 * @param array $columns
	 * @return \Illuminate\Database\Eloquent\Collection|Model
	 * @throws RepositoryException
	 */
    public function find(int $id, array $columns = ['*'])
	{
		$model = $this->model->newQuery()->findOrFail($id, $columns);
		$this->resetModel();

		return $model;
	}

	/**
	 * @param array $attributes
	 * @param int   $id
	 * @return Model
	 * @throws RepositoryException
	 */
	public function update(array $attributes, int $id): Model
	{
		/** @var $post Model */
		$model = $this->model->newQuery()->findOrFail($id);
		$model->fill($attributes);
		$model->save();

		$this->resetModel();

		return $model;
	}

    /**
     * @param array $data
     * @return Model
     */
    public function save(array $data): Model
    {
        return $this->model->newQuery()->create($data);
	}

	/**
	 * @param int $id
	 * @return boolean
	 * @throws Exception
	 */
	public function delete(int $id) : bool
	{
		return $this->model->newQuery()->findOrFail($id)->delete();
	}

    /**
     * Count elements
     *
     * @return int
     */
    public function count(): int
    {
        $count = Cache::get($this->model->getTable() . '_count', function () {
            return $this->model->count();
        });

        return $count;
    }

	/**
	 * @return Model
	 * @throws RepositoryException
	 */
	private function makeModel(): Model
	{
		$model = $this->application->make($this->model());

		if (!$model instanceof Model) {
			throw new RepositoryException(
				sprintf('Class %s, must be an instance of %s', $this->model, Model::class)
			);
		}

		return $this->model = $model;
	}

	/**
	 * @return Model
	 */
	public function getModel(): Model
	{
		return $this->model;
	}
}
