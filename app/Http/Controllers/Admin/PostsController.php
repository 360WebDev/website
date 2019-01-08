<?php
namespace App\Http\Controllers\Admin;

use App\Events\PostCreated;
use App\Forms\Admin\PostsForm;
use App\Model\Post;
use App\Model\User;
use App\Repository\PostRepository;
use App\Status;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class PostsController
 *
 * Admin posts controller, manages the addition and editing of new articles
 */
class PostsController extends AdminController
{

    protected $routePrefix = 'posts';

    protected $formClass = PostsForm::class;

    /**
     * @param PostRepository $postRepository
     * @return Response
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->getByOrderDesc();
        return response()->view('admin.posts.index', compact('posts'));
    }


    /**
     * @param Request $request
     * @param PostRepository $postRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, PostRepository $postRepository)
    {
        $post = $postRepository->save($this->getData($request));
        if ($post) {
            if ($request->hasFile('image_file')) {
                $imageFile = $request->file('image_file');
                $imageFile->move('posts', $post->getImageName($imageFile));
            }

            event(new PostCreated($post));

            return redirect(route('posts.index'))->with('success', "L'article a bien été ajouté");
        }

        return redirect()->back();
    }

	/**
	 * @param int            $id
	 * @param PostRepository $postRepository
	 * @param Guard          $guard
	 * @param string|null    $notification
	 * @return Response
	 * @throws \Exception
	 */
    public function edit(int $id, PostRepository $postRepository, Guard $guard, string $notification = null): Response
    {
		$post = $postRepository->getFirst($id);
		/** @var $user User */
		$user = $guard->user();
		$user->markAsReadNotification($notification);
        $form = $this->getForm($post);
        return response()->view('admin.posts.edit', compact('post', 'form'));
    }

	/**
	 * @param Request     $request
	 * @param Post        $post
	 * @return View
	 */
    public function update(Request $request, Post $post)
    {
    	$data = $this->getData($request);
    	if (isset($data['online']) && $data['online'] && ($data['status'] !== Status::ACCEPTED)) {
			return redirect()
				->route('posts.index')
				->with('error', "L'article doit être accepté pour le mettre en ligne.");
		}
        if ($post->update($data)) {
            if ($request->hasFile('image_file')) {
                $imageFile = $request->file('image_file');
                $imageFile->move('posts', $post->getImageName($imageFile));
            }
            return redirect(route('posts.index'))->with('success', "L'article a bien été édité");
        }
        return redirect()->back();
    }

    /**
     * @param Post $post
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->delete()) {
            return redirect(route('posts.index'))->with('success', "L'article a bien été supprimé.");
        }
        return redirect(route('posts.index'))->with('error', "L'article n'a pas pu être supprimé.");
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getData(Request $request): array
    {
        return array_merge($request->all(), ['image' => $request->file('image_file') ?? null]);
    }
}
