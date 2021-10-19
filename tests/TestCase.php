<?php

namespace Dillingham\SoftDeletesParent\Tests;

use Dillingham\SoftDeletesParent\SoftDeletesParentServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            SoftDeletesParentServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        Model::unguard();

        config()->set('database.default', 'testing');

        $migration = include __DIR__ . '/Fixtures/Database/create_authors_table.php.stub';
        $migration->up();
        $migration = include __DIR__ . '/Fixtures/Database/create_posts_table.php.stub';
        $migration->up();
    }
}
