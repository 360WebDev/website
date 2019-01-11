<?php
namespace App\Repository;

use App\Model\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
/**
 * App CommentRepository
 */
class CommentRepository extends Repository
{

    /**
	 * @var Comment
	 */
    protected $model;

    /**
     * @param int $postId
     * @return Builder
     */
    public function getAllByPost(int $postId) : Builder 
    {
        return $this->model->newQuery()->where('post_id', $postId)->orderBy('created_at', 'desc');
    }

    /**
     * @param int $id
     * @return Builder
     */
    public function getById(int $id) : Model
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    /**
	 * Specify model class name
	 * @return string
	 */
    public function model(): string 
    {
        return Comment::class;
    }

}