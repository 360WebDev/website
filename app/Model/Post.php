<?php

namespace App\Model;

use App\Favorite\Favorite;
use App\Status;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Michelf\Markdown;
use ReflectionClass;
use ReflectionException;

/**
 * Class Post
 */
class Post extends Model
{
    use HasSlug;

    protected $fillable = ['name', 'slug', 'image', 'content', 'category_id', 'user_id', 'online', 'status'];

    protected $with = ['user'];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns the content of the truncated post.
     *
     * @return string
     */
    public function shortContent(): string
    {
        return Str::limit($this->content);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * @return string
     */
    public function getOnlineToString(): string
    {
        return $this->getAttribute('online') ? 'Oui' : 'Non';
    }

	/**
	 * @return string
	 */
    public function getHtmlAttribute(): string
    {
        return Markdown::defaultTransform($this->getAttribute('content'));
    }

    /**
     * @param UploadedFile|string $image
     * @param null|string $type
     * @return string
     */
    public function getImageName($image, ?string $type = null): string
    {
        if (is_string($image)) {
            return $image;
        }
        $type = $type ? '-' . $type : '';
        return $this->id . $type . '.' . $image->clientExtension();
    }

    /**
     * @param null|string $type
     * @return string Return the image with the full path (/../public/posts/1.png)
     */
    public function getImage(?string $type = null): ?string
    {
        if ($this->image) {
            $fileName = $type
                ? $this->id . '-' . $type . '.' . pathinfo($this->image, PATHINFO_EXTENSION)
                : $this->image;
            return '/posts/' . $fileName;
        }
        return null;
    }


    /**
     * @return bool
     */
    public function favorited(): bool
    {
        return (bool)
            Favorite::where('user_id', Auth::id())
                ->where('post_id', $this->id)
                ->first();
    }

	/**
	 * @return string
	 */
    public function showBadgeToStatus(): string
	{
		switch ($this->status) {
			case Status::ACCEPTED :
				return 'badge-success';
				break;
			case Status::PENDING :
				return 'badge-warning';
				break;
			case Status::REJECT:
				return 'badge-danger';
				break;
			case Status::WRITING:
				return 'badge-primary';
				break;
			default:
				return 'badge-default';
				break;
		}
	}

    /**
     * Add order by created at
     *
     * @param Builder $query
     * @param string $order
     * @return Builder
     */
    public function scopeOrderByCreatedAt(Builder $query, string $order = 'desc') : Builder
    {
        return $query->orderBy('created_at', $order);
    }

	/**
	 * @return string[]
	 * @throws ReflectionException
	 */
    public function getStatus(): array
	{
		$status = [];
		foreach ((new ReflectionClass(Status::class))->getConstants() as $value) {
			$status[$value] = $value;
		}
		return $status;
	}

	/**
	 * true if post status is accpeted
	 *
	 * @return bool
	 */
	public function statusIsAccepted(): bool
	{
		return $this->status === Status::ACCEPTED;
	}
}
