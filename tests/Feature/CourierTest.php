<?php

namespace Tests\Feature;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourierTest extends TestCase
{
    use RefreshDatabase;

    public function test_courier_can_be_created_using_factory(): void
    {
        $courier = Courier::factory()->create([
            'name' => 'JNE Express',
            'type' => 'keduanya',
        ]);

        $this->assertDatabaseHas('couriers', [
            'id' => $courier->id,
            'name' => 'JNE Express',
            'type' => 'keduanya',
        ]);
    }

    public function test_courier_default_type_is_keduanya(): void
    {
        $courier = Courier::factory()->create([
            'type' => 'keduanya',
        ]);

        $this->assertEquals('keduanya', $courier->type);
    }

    public function test_unauthenticated_user_cannot_access_couriers(): void
    {
        $this->getJson('/couriers/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_courier(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/couriers', [
            'name' => 'J&T Express',
            'type' => 'umum',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'J&T Express',
                'type' => 'umum',
            ]);

        $this->assertDatabaseHas('couriers', [
            'name' => 'J&T Express',
            'type' => 'umum',
        ]);
    }

    public function test_create_courier_validation_requires_unique_name(): void
    {
        $user = User::factory()->create();
        Courier::factory()->create(['name' => 'Existing Courier']);

        $response = $this->actingAs($user)->postJson('/couriers', [
            'name' => 'Existing Courier',
            'type' => 'keduanya',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_authenticated_user_can_show_courier(): void
    {
        $user = User::factory()->create();
        $courier = Courier::factory()->create();

        $response = $this->actingAs($user)->getJson('/couriers/'.$courier->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $courier->id,
                'name' => $courier->name,
                'type' => $courier->type,
            ]);
    }

    public function test_authenticated_user_can_update_courier(): void
    {
        $user = User::factory()->create();
        $courier = Courier::factory()->create(['name' => 'Old Name', 'type' => 'umum']);

        $response = $this->actingAs($user)->putJson('/couriers/'.$courier->id, [
            'name' => 'New Name',
            'type' => 'marketplace',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Name',
                'type' => 'marketplace',
            ]);

        $this->assertDatabaseHas('couriers', [
            'id' => $courier->id,
            'name' => 'New Name',
            'type' => 'marketplace',
        ]);
    }

    public function test_authenticated_user_can_delete_courier(): void
    {
        $user = User::factory()->create();
        $courier = Courier::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/couriers/'.$courier->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('couriers', [
            'id' => $courier->id,
        ]);
    }

    public function test_index_route_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/couriers');

        $response->assertStatus(405);
    }
}
