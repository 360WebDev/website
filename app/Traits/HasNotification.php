<?php
namespace App\Traits;

use Illuminate\Notifications\DatabaseNotification;

/**
 * Trait HasNotification
 */
trait HasNotification
{

	/**
	 * @param string $id
	 * @return void
	 */
	public function markAsReadNotification(string $id): void
	{
		/** @var $notification DatabaseNotification */
		$notification = $this->notifications()->where('id', $id)->first();
		if ($notification) {
			$notification->markAsRead();
		}
	}


}
