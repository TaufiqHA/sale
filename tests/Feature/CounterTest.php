<?php

namespace Tests\Feature;

use App\Models\Counter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounterTest extends TestCase
{
    use RefreshDatabase;

    public function test_counter_can_be_created_using_factory(): void
    {
        $counter = Counter::factory()->create([
            'name' => 'Counter A',
            'address' => '123 Main St',
            'phone' => '123-456-7890',
            'description' => 'First counter',
        ]);

        $this->assertDatabaseHas('counters', [
            'id' => $counter->id,
            'name' => 'Counter A',
            'address' => '123 Main St',
            'phone' => '123-456-7890',
            'description' => 'First counter',
            'status' => true,
        ]);
    }

    public function test_counter_has_default_status_as_true(): void
    {
        // Let's create a model instance directly without specifying status to verify default attributes
        $newCounter = Counter::create([
            'name' => 'Counter B',
            'address' => '456 Elm St',
            'phone' => '987-654-3210',
            'description' => 'Second counter',
        ]);

        $this->assertTrue($newCounter->status);
        $this->assertDatabaseHas('counters', [
            'id' => $newCounter->id,
            'status' => true,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_counters(): void
    {
        $this->getJson('/counters')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_counters(): void
    {
        $user = User::factory()->create();
        Counter::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/counters');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_create_counter(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/counters', [
            'name' => 'Cashier Counter 1',
            'address' => 'Ground Floor',
            'phone' => '555-0199',
            'description' => 'Main checkout counter',
            'status' => true,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Cashier Counter 1',
                'address' => 'Ground Floor',
                'phone' => '555-0199',
                'description' => 'Main checkout counter',
                'status' => true,
            ]);

        $this->assertDatabaseHas('counters', [
            'name' => 'Cashier Counter 1',
        ]);
    }

    public function test_authenticated_user_can_show_counter(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create();

        $response = $this->actingAs($user)->getJson('/counters/'.$counter->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $counter->id,
                'name' => $counter->name,
            ]);
    }

    public function test_authenticated_user_can_update_counter(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->putJson('/counters/'.$counter->id, [
            'name' => 'New Name',
            'address' => 'Updated Address',
            'phone' => '555-9999',
            'description' => 'Updated Description',
            'status' => false,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Name',
                'address' => 'Updated Address',
                'phone' => '555-9999',
                'description' => 'Updated Description',
                'status' => false,
            ]);

        $this->assertDatabaseHas('counters', [
            'id' => $counter->id,
            'name' => 'New Name',
            'status' => false,
        ]);
    }

    public function test_authenticated_user_can_delete_counter(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/counters/'.$counter->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('counters', [
            'id' => $counter->id,
        ]);
    }

    public function test_authenticated_user_can_view_counter_index_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/counters');

        $response->assertStatus(200)
            ->assertViewIs('administrator.counter');
    }
}
