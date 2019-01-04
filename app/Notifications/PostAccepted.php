<?php

namespace App\Notifications;

use App\Model\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class PostAccepted
 */
class PostAccepted extends Notification
{
    use Queueable;
	/**
	 * @var Post
	 */
	private $post;

	/**
	 * Create a new notification instance.
	 *
	 * @param Post $post
	 */
    public function __construct(Post $post)
    {
		$this->post = $post;
	}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        	'id'     => $this->post->getAttribute('id'),
        	'title'  => $this->post->getAttribute('name'),
			'slug'   => $this->post->getAttribute('slug'),
			'name'   => 'Un nouvel article à valider',
			'author' => $this->post->user()->first()->username,
			'status' => $this->post->getAttribute('status')
        ];
    }
}
