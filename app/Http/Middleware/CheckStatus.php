<?php

namespace App\Http\Middleware;

use App\Model\Post;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckStatus
{

	/**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	$post_id = last(explode('/', $request->getPathInfo()));
    	/** @var $currentPost Post */
    	$currentPost = $request->user()->posts()->where('id', (int) $post_id)->first();
    	if ($currentPost->statusIsAccepted() && !$request->user()->isAdmin()) {
    		return redirect()
				->route('user.posts')
				->with('danger', 'Vous ne pouvez pas modifier un article accepté.');
		}
        return $next($request);
    }
}
