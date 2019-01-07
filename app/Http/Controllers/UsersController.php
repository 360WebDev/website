<?php

namespace App\Http\Controllers;

use App\Forms\UserPostForm;
use App\Model\Post;
use App\Notifications\PostAccepted;
use App\Repository\PostRepository;
use App\Model\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Services\DiscordService;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;
use Spatie\MediaLibrary\Exceptions\FileCannotBeAdded;

/**
 * Class UsersController
 */
class UsersController extends Controller
{

    /**
	 * @var PostRepository
	 */
	private $postRepository;

	/**
	 * @var UserRepository
	 */
	private $userRepository;

	public function __construct(PostRepository $postRepository, UserRepository $userRepository)
	{
		$this->postRepository = $postRepository;
		$this->userRepository = $userRepository;
	}

	/**
     * @return \Illuminate\Http\Response
     */
    public function myFavorites(): Response
    {
        $myFavorites = Auth::user()->favorites;
        return response()->view('users.favorites', compact('myFavorites'));
    }

    /**
     * @return Response
     */
    public function myAccount(): Response
    {
        return response()->view('users.account', ['user' => Auth::user()]);
    }

    /**
     * @param Request        $request
     * @param DiscordService $discord
     * @param RoleRepository $roleRepository
     * @param Guard          $guard
     * @return RedirectResponse
     * @throws FileCannotBeAdded
     */
    public function update(
        Request $request,
        DiscordService $discord,
        RoleRepository $roleRepository,
        Guard $guard
    ): RedirectResponse {
        /** @var $user User */
        $user = $guard->user();
        $discord_id = $request->input('discord_id') ?? null;
        $data       = [];
        if ($discord_id) {
            $memberRoles = $discord->getMemberRoles($discord_id);
            if (in_array('@admin', $memberRoles)) {
                $role_admin      = $roleRepository->getBySlug('admin');
                $data['role_id'] = $role_admin->id;
            }
        }
        if ($discord_id && $request->has('use_discord')) {
            $discord_user   = $discord->getUser($discord_id);
            $data['name']   = $discord_user->username;
            $user
                ->addMediaFromUrl($discord_user->getAvatar())
                ->toMediaCollection('avatars');
        }
        if ($user->update(array_merge($request->all(), $data))) {
            return redirect()->route('user.account')->with('success', 'Votre compte a bien été mis à jour');
        }
        return redirect()->back()->with('danger', 'Votre compte n\'a pas pu être à jour.');
    }

	/**
	 * @param PostRepository $postRepository
	 * @return Response
	 */
	public function posts(): Response
	{
		$posts = $this->postRepository->currentUserPosts();
		return \response()->view('users.posts', compact('posts'));
	}

	/**
	 * @param Request $request
	 * @param FormBuilder $formBuilder
	 * @return Response
	 */
	public function addPost(Request $request, FormBuilder $formBuilder)
	{
		$form = $formBuilder->create(
			UserPostForm::class, ['method' => 'POST', 'model' => new Post()]
		);
		if ($request->method() === 'POST') {
			$form->redirectIfNotValid();
			$this->postRepository->saveUserPost($form->getFieldValues(), Auth::user());
			return redirect()
				->route('user.posts')
				->with('success', 'Votre article a bien été ajouté. Un admin doit le valider');
		}
		return response()->view('users.add_post', compact('form'));
	}

	/**
	 * @param Post        $post
	 * @param Request     $request
	 * @param FormBuilder $formBuilder
	 * @param Guard       $guard
	 * @return Response
	 */
	public function updatePost(Post $post, Request $request, FormBuilder $formBuilder, Guard $guard)
	{
		/** @var $user User */
		$user = $guard->user();
		$form = $formBuilder->create(UserPostForm::class, ['model' => $post, 'method' => 'PUT']);
		if ($request->getMethod() === 'PUT') {
			$form->redirectIfNotValid();
			$this->postRepository->saveUserPost($form->getFieldValues(), $user, true);
			if ($request->exists('validated')) {
				// Send notification only for admin
				$usersAdmin = $this->userRepository->findAllAdmin();
				$usersAdmin->map(function (User $user) use ($post) {
					$user->notify(new PostAccepted($post));
				});
			}
			return redirect()
				->route('user.posts')
				->with('success', 'Votre article a bien été modifié.');
		}
		return \response()->view('users.update_post', compact('form', 'post'));
	}
}
