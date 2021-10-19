<?php

namespace Dillingham\SoftDeletesParent;

use Dillingham\SoftDeletesParent\Tests\Fixtures\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait SoftDeletesParent
{
    protected static function bootSoftDeletesParent()
    {
        static::addGlobalScope(new SoftDeletesParentScope);

        static::$dispatcher->listen('eloquent.deleting: '. static::$softDeletesParent, function(Model $model) {
            static::query()->where([
                $model->getForeignKey() => $model->getKey()
            ])->update(['parent_deleted_at' => now()]);
        });

        static::$dispatcher->listen('eloquent.restoring: '. static::$softDeletesParent, function($model) {
            static::query()->withParentTrashed()->where([
                $model->getForeignKey() => $model->getKey()
            ])->update(['parent_deleted_at' => null]);
        });
    }

    public static function scopeWithParentTrashed(Builder $query)
    {
        $query->withoutGlobalScope(new SoftDeletesParentScope());
    }

    public static function scopeOnlyParentTrashed(Builder $query)
    {
        $query->withoutGlobalScope(new SoftDeletesParentScope());

        $query->whereNotNull('parent_deleted_at');
    }
}
