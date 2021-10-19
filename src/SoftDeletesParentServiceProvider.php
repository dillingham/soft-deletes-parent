<?php

namespace Dillingham\SoftDeletesParent;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class SoftDeletesParentServiceProvider extends ServiceProvider
{
    public function register()
    {
        Blueprint::macro('softDeletesParent', function() {
            return $this->timestamp('parent_deleted_at')->nullable();
        });
    }

    public function boot()
    {
        //
    }
}
