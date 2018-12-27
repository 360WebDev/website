<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Repository\PostRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IncrementPostCountCache
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * Create the event listener.
     *
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        if (Cache::has('posts_count')) {
            Cache::increment('posts_count');
        } else {
            Cache::forever('posts_count', $this->postRepository->count() + 1);
        }
    }
}
