<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created_using_factory(): void
    {
        $category = Category::factory()->create([
            'name' => 'Electronics',
            'description' => 'Electronic items',
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Electronics',
            'description' => 'Electronic items',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_categories(): void
    {
        $this->getJson('/categories')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_categories(): void
    {
        $user = User::factory()->create();
        Category::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/categories');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/categories', [
            'name' => 'Food & Beverages',
            'description' => 'Food items',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Food & Beverages',
                'description' => 'Food items',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Food & Beverages',
        ]);
    }

    public function test_create_category_validation_requires_unique_name(): void
    {
        $user = User::factory()->create();
        Category::factory()->create(['name' => 'Existing Category']);

        $response = $this->actingAs($user)->postJson('/categories', [
            'name' => 'Existing Category',
            'description' => 'Details',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_authenticated_user_can_show_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->getJson('/categories/'.$category->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $category->id,
                'name' => $category->name,
            ]);
    }

    public function test_authenticated_user_can_update_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->putJson('/categories/'.$category->id, [
            'name' => 'New Name',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Name',
                'description' => 'Updated Description',
            ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'New Name',
        ]);
    }

    public function test_authenticated_user_can_delete_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/categories/'.$category->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
