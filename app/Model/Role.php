<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Class Role
 */
class Role extends Model
{

    const ADMIN  = 'admin';
    const MEMBER = 'member';

    protected $fillable = ['name', 'slug', 'description'];

    /**
     * @return Relation
     */
    public function users(): Relation
    {
        return $this->hasMany(User::class);
    }
}
