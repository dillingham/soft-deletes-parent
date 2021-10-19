<?php

namespace Dillingham\SoftDeletesParent\Tests\Fixtures;

use Dillingham\SoftDeletesParent\SoftDeletesParent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    use SoftDeletesParent;

    protected static $softDeletesParent = Author::class;
}
