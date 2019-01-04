<?php
namespace App\Repository;

use App\Model\Post;
use App\Model\User;
use App\Status;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * App PostRepository
 */
class PostRepository extends Repository
{

    /**
     * @var Post
     */
    protected $model;

    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getByOrderDesc(): Collection
    {
        return $this->model->orderByCreatedAt()->get();
    }

    /**
     * @param int $id
     * @return Model|Post
     */
    public function getFirst(int $id): Model
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    /**
     * @param array $data
     * @return int
     */
    public function update(array $data): int
    {
        return $this->model->newQuery()->update($data);
    }

	/**
	 * @param int|null $categoryId
	 * @return Builder
	 */
	public function findIsOnline(?int $categoryId = null): Builder
	{
		$resultQuery = $this->model->newQuery()->with('category', 'user')
			->where('online', true)
			->orderBy('created_at', 'desc');
		if ($categoryId) {
			return $resultQuery->where('category_id', $categoryId);
		}
		return $resultQuery;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder[]|Collection
	 */
	public function currentUserPosts()
	{
		return $this->model->newQuery()
			->with('user')
			->where('user_id', Auth::id())
			->orderBy('created_at', 'desc')
			->get();
	}

	/**
	 * @param string[] $data
	 * @param User     $user
	 * @param bool     $update
	 * @return Model|int
	 */
	public function saveUserPost(array $data, User $user, bool $update = false)
	{
		$isAdmin = $user->isAdmin();
		if ((isset($data['validated']) && $data['validated']) && !$isAdmin) {
			$data['status'] = Status::PENDING;
		} elseif ($isAdmin) {
			$data['status'] = Status::ACCEPTED;
		} else {
			$data['status'] = Status::WRITING;
		}
		unset($data['validated']);
		$data['online'] = false;
		$data['user_id'] = $user->id;
		return $update ? $this->update($data) : $this->save($data);
	}
}
