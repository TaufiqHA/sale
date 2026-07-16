<?php

namespace Tests\Feature;

use App\Models\Expedition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpeditionTest extends TestCase
{
    use RefreshDatabase;

    public function test_expedition_can_be_created_using_factory(): void
    {
        $expedition = Expedition::factory()->create([
            'name' => 'JNE Express',
        ]);

        $this->assertDatabaseHas('expeditions', [
            'id' => $expedition->id,
            'name' => 'JNE Express',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_expeditions(): void
    {
        $this->getJson('/expeditions/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_expedition(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/expeditions', [
            'name' => 'J&T Express',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'J&T Express',
            ]);

        $this->assertDatabaseHas('expeditions', [
            'name' => 'J&T Express',
        ]);
    }

    public function test_create_expedition_validation_requires_unique_name(): void
    {
        $user = User::factory()->create();
        Expedition::factory()->create(['name' => 'Existing Expedition']);

        $response = $this->actingAs($user)->postJson('/expeditions', [
            'name' => 'Existing Expedition',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_authenticated_user_can_show_expedition(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::factory()->create();

        $response = $this->actingAs($user)->getJson('/expeditions/'.$expedition->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $expedition->id,
                'name' => $expedition->name,
            ]);
    }

    public function test_authenticated_user_can_update_expedition(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->putJson('/expeditions/'.$expedition->id, [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Name',
            ]);

        $this->assertDatabaseHas('expeditions', [
            'id' => $expedition->id,
            'name' => 'New Name',
        ]);
    }

    public function test_authenticated_user_can_delete_expedition(): void
    {
        $user = User::factory()->create();
        $expedition = Expedition::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/expeditions/'.$expedition->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('expeditions', [
            'id' => $expedition->id,
        ]);
    }

    public function test_index_route_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/expeditions');

        $response->assertStatus(405);
    }
}
