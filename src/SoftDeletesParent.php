<?php

namespace Dillingham\SoftDeletesParent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

trait SoftDeletesParent
{
    protected static function bootSoftDeletesParent()
    {
        static::addGlobalScope(new SoftDeletesParentScope());
    }

    public static function softDeletesParent($parent)
    {
        Event::listen('eloquent.deleting: '. $parent, function (Model $model) {
            static::query()->where([
                $model->getForeignKey() => $model->getKey(),
            ])->update(['parent_deleted_at' => now()]);
        });

        Event::listen('eloquent.restoring: '. $parent, function (Model $model) {
            static::query()->withParentTrashed()->where([
                $model->getForeignKey() => $model->getKey(),
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
