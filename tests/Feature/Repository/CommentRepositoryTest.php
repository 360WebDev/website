<?php

namespace Tests\Feature\CommentRepositoryTest;

use App\Repository\RepositoryException;
use Tests\Feature\Repository\Stubs\DummyRepository;
use Tests\Feature\Repository\Stubs\FailRepository;
use Tests\Feature\Repository\Stubs\FooModel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Model\Comment;
use App\Model\Post;
use App\Repository\CommentRepository;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

	/**
	 * @test
	 *
	 * @throws RepositoryException
	 */
	public function correct_id_for_get_by_id()
	{	
		$post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create(['content' => 'content', 'post_id' => $post->id, 'user_id' => 1]);
        $commentRepository = new CommentRepository($this->app);
        $this->assertEquals($comment->id, $commentRepository->getById($comment->id)->id);
	}
	
	/**
	 * @test
	 *
	 * @throws RepositoryException
	 */
	public function correct_post_id_for_get_all_by_post()
	{
		$post = factory(Post::class)->create();
		$comment = factory(Comment::class)->create(['content' => 'content', 'post_id' => $post->id, 'user_id' => 1]);
		$commentRepository = new CommentRepository($this->app);
		$this->assertEquals($comment->post_id, $commentRepository->getAllByPost($comment->post_id)->first()->post_id);
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function test_update_comment()
	{
		$comment1 = factory(Comment::class)->create(['content' => 'content 1', 'post_id' => 1, 'user_id' => 1]);
		$comment2 = factory(Comment::class)->create(['content' => 'content 2', 'post_id' => 1, 'user_id' => 1]);
		$comment3 = factory(Comment::class)->create(['content' => 'content 3', 'post_id' => 1, 'user_id' => 1]);

		$commentRepository = new CommentRepository($this->app);
		$data = [
			'content' => 'First comment',
			'post_id' => 1,
			'user_id' => 1,
		];

		$comment1 = $commentRepository->update($data, $comment1->id);

		$this->assertEquals('First comment', $comment1->content);
		$this->assertEquals('content 2', $comment2->content);
		$this->assertEquals('content 3', $comment3->content);
	}

	/**
	 * @test
	 * @throws RepositoryException
	 */
	public function test_save_comment()
	{
		$data = ['content' => 'content 1',
				'post_id' => 1,
				'user_id' => 1];

		$comment = factory(Comment::class)->create($data);

		$commentRepository = new CommentRepository($this->app);

		$commentRepository = $commentRepository->save($data);

		$this->assertEquals($comment->content, $commentRepository->content);
		$this->assertEquals($comment->post_id, $commentRepository->post_id);
		$this->assertEquals($comment->user_id, $commentRepository->user_id);
	}

	/**
	 * @test
	 *
	 * @throws RepositoryException
	 */
	public function test_delete_comment()
	{	
		$post = factory(Post::class)->create();
        $comment = factory(Comment::class)->create(['content' => 'content', 'post_id' => $post->id, 'user_id' => 1]);
        $commentRepository = new CommentRepository($this->app);
        $this->assertEquals($comment->id, $commentRepository->delete($comment->id));
	}

}
