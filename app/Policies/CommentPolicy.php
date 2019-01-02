<?php

namespace App\Policies;

use App\Model\User;
use App\Model\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the comment.
     *
     * @param  \App\User  $user
     * @param  \App\Comment  $comment
     * @return mixed
     */
    public function view(User $user, Comment $comment)
    {
        //
    }

    /**
     * Determine whether the user can create comments.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the comment.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Comment  $comment
     * @return mixed
     */
    public function update(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id || $user->role != 'membre';
    }

    /**
     * Determine whether the user can delete the comment.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Comment  $comment
     * @return mixed
     */
    public function delete(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id || $user->role != 'membre';
    }
}
