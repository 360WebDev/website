<?php

namespace Tests\Feature\Repository;

use App\Model\Category;
use App\Model\Post;
use App\Model\Role;
use App\Model\User;
use App\Repository\PostRepository;
use App\Repository\RepositoryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostRepositoryTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 *
	 * @throws RepositoryException
	 */
	public function correct_status_for_add_member_post()
	{
		factory(Category::class)->create();
		Role::create(['name' => 'member', 'slug' => 'member', 'description' => 'member']);
		$user = factory(User::class)->create(['role_id' => Role::first()]);
		factory(User::class)->create();
		$postRepository = new PostRepository($this->app);
		$data = [
			'name' => 'foo',
			'slug' => 'foo',
			'content' => 'foobar',
			'category_id' => Category::first(),
			'user_id' => User::first(),
			'status' => 'pending',
			'validated' => true
		];
		$post = $postRepository->saveUserPost($data, $user);

		$this->assertEquals('pending', $post->status);
	}

	/**
	 * @test
	 *
	 * @throws RepositoryException
	 */
	public function status_accepted_if_is_admin_role()
	{
		$category = factory(Category::class)->create();
		Role::create(['name' => 'admin', 'slug' => 'admin', 'description' => 'admin']);
		$userAdmin = factory(User::class)->create(['role_id' => Role::first()]);
		factory(User::class)->create();
		$postRepository = new PostRepository($this->app);
		$data = [
			'name' => 'foo',
			'slug' => 'foo',
			'content' => 'foobar',
			'category_id' => $category->id,
			'user_id' => $userAdmin->id,
			'status' => 'pending'
		];
		$post = $postRepository->saveUserPost($data, $userAdmin);

		$this->assertEquals('accepted', $post->status);
	}

	/**
	 * @test
	 *
	 * @throws RepositoryException
	 */
	public function post_update()
	{
		$post1 = factory(Post::class)->create(['name' => 'Premier article', 'content' => 'content 1']);
		$post2 = factory(Post::class)->create(['name' => 'Second article', 'content' => 'content 2']);
		$post3 = factory(Post::class)->create(['name' => 'dernier article', 'content' => 'content 3']);

		$postRepository = new PostRepository($this->app);
		$data = [
			'name' => 'Premier article',
			'slug' => 'foo',
			'content' => 'foobar',
		];

		$post1 = $postRepository->update($data, $post1->id);


		$this->assertEquals('foobar', $post1->content);
		$this->assertEquals('content 2', $post2->content);
		$this->assertEquals('content 3', $post3->content);
	}

	/**
	 * @test
	 *
	 * @throws RepositoryException
	 */
	public function update_post_who_not_exist()
	{
		factory(Post::class, 4)->create();

		$postRepository = new PostRepository($this->app);
		$data = [
			'name' => 'Premier article',
			'slug' => 'foo',
			'content' => 'foobar',
		];

		$this->expectException(ModelNotFoundException::class);
		$postRepository->update($data, 256);

	}

}
