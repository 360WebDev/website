<?php

namespace App\Model;

use App\Favorite\HasFavorites;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasFavorites;
    use Notifiable;
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role_id', 'discord_id', 'username'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * @var array
     */
    private $conversionsAvatar = [
        'thumb' => [40,  40],
        'large' => [150, 150]
    ];

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role ? $this->role->name : '';
    }

    public function setDiscordIdAttribute($value)
    {
        $this->attributes['discord_id'] = intval($value);
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role && ($this->role->slug === Role::ADMIN);
    }

    /**
     * @param string $role
     * @return bool True if the parameter role is the same as the connected user.
     */
    public function hasRole(string $role): bool
    {
        return $this->role && ($this->role->slug === $role);
    }

    /**
     * @return string
     */
    public function getDiscordId(): ?string
    {
        return $this->getAttribute('discord_id') ?? null;
    }

    /**
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null) : void
    {
        foreach ($this->conversionsAvatar as $conversion => [$width, $height]) {
            $this
                ->addMediaConversion($conversion)
                ->width($width)
                ->height($height);
        }
    }

    /**
     * Retrieve the link from the user's avatar. If the user has not added his avatar,
     * define a default avatar managed by the Gravatar service
     *
     * @param string $conversionName
     * @param int    $size
     * @return string
     */
    public function getAvatarUrl(string $conversionName = 'thumb'): string
    {
        $mediaCollection = $this->getMedia('avatars');
        if ($mediaCollection->isEmpty()) {
            $size = $this->conversionsAvatar[$conversionName][0];
            $gravatarEmail = md5(strtolower(trim($this->email)));
            return sprintf('https://www.gravatar.com/avatar/%s?s=%s', $gravatarEmail, $size);
        }
        return $mediaCollection->first()->getUrl($conversionName);
    }
}
