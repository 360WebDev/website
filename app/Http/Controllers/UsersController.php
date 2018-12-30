<?php

namespace App\Http\Controllers;

use App\Forms\UserPostForm;
use App\Model\Post;
use App\Notifications\PostAccepted;
use App\Repository\PostRepository;
use App\Repository\RoleRepository;
use App\Service\DiscordService;
use App\Status;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;

/**
 * Class UsersController
 */
class UsersController extends Controller
{

    /**
	 * @var PostRepository
	 */
	private $postRepository;

	public function __construct(PostRepository $postRepository)
	{
		$this->postRepository = $postRepository;
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
     * @param Request $request
     * @param DiscordService $discord
     * @param RoleRepository $roleRepository
     * @return RedirectResponse
     */
    public function update(Request $request, DiscordService $discord, RoleRepository $roleRepository): RedirectResponse
    {
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
            $data['avatar'] = $discord_user->getAvatar();
        }
        if (Auth::user()->update(array_merge($request->all(), $data))) {
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
		$form = $formBuilder->create(UserPostForm::class, ['method' => 'POST']);
		if ($request->method() === 'POST') {
			$form->redirectIfNotValid();
			$this->postRepository->saveUserPost($form->getFieldValues(), Auth::user()->isAdmin());
			return redirect()
				->route('user.posts')
				->with('success', 'Votre article a bien été ajouté. Un admin doit le valider');
		}
		return response()->view('users.add_post', compact('form'));
	}

	/**
	 * @param Post $post
	 * @param Request $request
	 * @param FormBuilder $formBuilder
	 * @return Response
	 */
	public function updatePost(Post $post, Request $request, FormBuilder $formBuilder)
	{
		$form = $formBuilder->create(UserPostForm::class, ['model' => $post, 'method' => 'PUT']);
		if ($request->getMethod() === 'PUT') {
			$form->redirectIfNotValid();
			$this->postRepository->saveUserPost($form->getFieldValues(), Auth::user()->isAdmin(), true);
			if ($request->exists('validated')) {
				// Send notification only for admin
				//$usersAdmin = $this->->findAllAdmin();
				$post->notify(new PostAccepted($post));
			}
			return redirect()
				->route('user.posts')
				->with('success', 'Votre article a bien été modifié.');
		}
		return \response()->view('users.update_post', compact('form', 'post'));
	}
}
