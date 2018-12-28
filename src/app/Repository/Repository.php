<?php
namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
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
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Collection|Model
     */
    public function getBySlug(string $slug)
    {
        return $this->model->newQuery()->where('slug', $slug)->firstOrFail();
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
     * Count elements
     *
     * @return int
     */
    public function count(): int
    {
        $count = Cache::get($this->model->getTable().'_count', function () {
            return $this->model->count();
        });

        return $count;
    }
}
