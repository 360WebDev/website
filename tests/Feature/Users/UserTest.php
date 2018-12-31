<?php
namespace Tests\Feature\Users;

use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\MockObject\MockObject;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Symfony\Component\HttpFoundation\ServerBag;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;

	/**
	 * Test method User::getAvatarUrl()
	 *
	 * @test
	 */
	public function get_default_avatar_url()
	{
		factory(User::class)->create(['email' => 'foor@bar.com']);

		/** @var $user User */
		$user = User::first();

		$expected = sprintf('https://www.gravatar.com/avatar/%s?s=40', md5($user->email));

		$this->assertCount(0, $user->getMedia('avatars'));
		$this->assertEquals($expected, $user->getAvatarUrl());
	}

	/**
	 * Test method User::getAvatarUrl()
	 *
	 * @test
	 */
	public function get_avatar_url_with_media_collection()
	{

		$stub = __DIR__ . '/avatar.jpg';
		$name = str_random(8) . '.jpg';
		$path = sys_get_temp_dir() . '/' . $name;

		copy($stub, $path);

		$uploadedFile = new UploadedFile(
			$path,
			'avatar',
			'image/png',
			filesize($path),
			null,
			true
		);


		factory(User::class)->create();

		/** @var $user User */
		$user = User::first();
		$user->addMedia($uploadedFile)->toMediaCollection('avatars');


		$this->assertCount(1, $user->getMedia('avatars'));
		$this->assertEquals('/storage/1/conversions/avatar-thumb.jpg', $user->getAvatarUrl());
	}

}
