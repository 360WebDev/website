<?php

namespace App\Http\Controllers\Admin;

use App\Model\Post;
use App\Http\Controllers\Controller;
use App\Repository\PostRepository;
use Illuminate\View\View;

class DashboardController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index(): View
    {
        $postCount = $this->postRepository->count();

        return view('admin.dashboard.index', ['posts_count' => $postCount]);
    }
}
