<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestCategeory extends TestCase
{
    use RefreshDatabase;

    public function testCreateSuccess(): void
    {
        Category::create(['name' => 'Category 1']);
        Category::create(['name' => 'Category 2']);
        Category::create(['name' => 'Category 3']);

        $categories = Category::orderBy('name', 'asc')->get();

        $this->assertCount(3, $categories);

        $slugCategory1 = $categories[0]->slug;
        $this->assertSame('category-1', $slugCategory1);
    }

    public function testCreateFailed(): void
    {
        $this->expectException(QueryException::class);

        Category::create(['name' => null]);
    }

    public function testGenerateSlug(): void
    {
        Category::create(['name' => 'Category 1']);

        $category = Category::first();

        $this->assertSame('category-1', $category->slug);
    }

    public function testUpdate(): void
    {
        $category = Category::create(['name' => 'Category 1']);

        $category->update(['name' => 'Category Updated']);

        $this->assertSame('Category Updated', $category->fresh()->name);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Category Updated',
        ]);
        $this->assertDatabaseMissing('categories', [
            'name' => 'Category 1',
        ]);
    }

    public function testSlugAfterUpdate(): void
    {
        $category = Category::create(['name' => 'Category 1']);

        $category->update(['name' => 'Category Updated']);

        $this->assertSame('category-updated', $category->fresh()->slug);
    }

    public function testSlugWithDuplicateName(): void
    {
        $this->expectException(QueryException::class);

        $first = Category::create(['name' => 'Category 1']);
        $second = Category::create(['name' => 'Category 1']);
    }

    public function testDelete(): void
    {
        $category = Category::create(['name' => 'Category 1']);

        $category->delete();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function testHasManyCourses(): void
    {
        $user = User::factory(1)->create()[0];

        $category = Category::create(['name' => 'Category 1']);
        $category->courses()->create([
            'mentor_id' => $user->id,
            'title' => 'Course 1',
            'description' => 'Description of course 1',
        ]);

        $this->assertCount(1, $category->courses);
    }
}
