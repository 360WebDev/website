<?php
namespace Tests\Feature\Posts;

use App\Model\Post;
use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionException;
use Tests\TestCase;

class StatusTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * @test
	 * @throws ReflectionException
	 */
	public function all_status_to_constants_class()
	{
		$user = factory(User::class)->create();
		/** @var $post Post */
		$post = factory(Post::class)->create(['user_id' => $user->id]);

		$expected = [
			'pending' => 'pending',
			'reject'  => 'reject',
			'accepted' => 'accepted',
			'writing' => 'writing'
		];
		$this->assertEquals($expected, $post->getStatus());
	}

}
