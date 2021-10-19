<?php

namespace Dillingham\SoftDeletesParent\Tests;

use Carbon\Carbon;
use Dillingham\SoftDeletesParent\Tests\Fixtures\Author;
use Dillingham\SoftDeletesParent\Tests\Fixtures\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class SoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_model_of_soft_deleted_parent()
    {
        $author = Author::create(['name' => 'Brian']);

        $deleted = Post::create([
            'title' => 'Hello World',
            'author_id' => $author->id,
            'parent_deleted_at' => now(),
        ]);

        $active = Post::create([
            'title' => 'Hello World',
            'author_id' => $author->id,
            'parent_deleted_at' => null,
        ]);

        $this->assertEquals(1, Post::count());
        $this->assertEquals(2, Post::withParentTrashed()->count());
        $this->assertEquals(1, Post::onlyParentTrashed()->count());
        $this->assertTrue(Post::withParentTrashed()->get()->contains($active));
        $this->assertTrue(Post::withParentTrashed()->get()->contains($deleted));
        $this->assertFalse(Post::onlyParentTrashed()->get()->contains($active));
        $this->assertFalse(Post::all()->contains($deleted));
    }

    public function test_soft_deleting_parent()
    {
        $this->travelTo(Carbon::parse('01/01/2021 9pm'));

        $author = Author::create(['name' => 'Brian']);

        Post::create([
            'title' => 'Hello World',
            'author_id' => $author->id,
        ]);

        $author->delete();

        $this->assertEquals(
            '2021-01-01 21:00:00',
            DB::table('posts')->first()->parent_deleted_at
        );
    }

    public function test_restoring_parent()
    {
        $author = Author::create(['name' => 'Brian']);

        Post::create([
            'title' => 'Hello World',
            'author_id' => $author->id,
        ]);

        $author->delete();
        $author->restore();

        $this->assertNull(DB::table('posts')->first()->parent_deleted_at);
    }
}
