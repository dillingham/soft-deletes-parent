<?php

namespace Dillingham\SoftDeletesParent\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    public function posts():HasMany
    {
        return $this
            ->hasMany(Post::class)
            ->whereNotNull('parent_deleted_at');
    }
}
