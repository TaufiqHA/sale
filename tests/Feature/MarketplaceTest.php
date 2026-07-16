<?php

namespace Tests\Feature;

use App\Models\Marketplace;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketplace_can_be_created_using_factory(): void
    {
        $marketplace = Marketplace::factory()->create([
            'name' => 'Tokopedia',
        ]);

        $this->assertDatabaseHas('marketplaces', [
            'id' => $marketplace->id,
            'name' => 'Tokopedia',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_marketplaces(): void
    {
        $this->getJson('/marketplaces/1')->assertStatus(401);
    }

    public function test_authenticated_user_can_create_marketplace(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/marketplaces', [
            'name' => 'Shopee',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Shopee',
            ]);

        $this->assertDatabaseHas('marketplaces', [
            'name' => 'Shopee',
        ]);
    }

    public function test_create_marketplace_validation_requires_unique_name(): void
    {
        $user = User::factory()->create();
        Marketplace::factory()->create(['name' => 'Existing Marketplace']);

        $response = $this->actingAs($user)->postJson('/marketplaces', [
            'name' => 'Existing Marketplace',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_authenticated_user_can_show_marketplace(): void
    {
        $user = User::factory()->create();
        $marketplace = Marketplace::factory()->create();

        $response = $this->actingAs($user)->getJson('/marketplaces/'.$marketplace->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $marketplace->id,
                'name' => $marketplace->name,
            ]);
    }

    public function test_authenticated_user_can_update_marketplace(): void
    {
        $user = User::factory()->create();
        $marketplace = Marketplace::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->putJson('/marketplaces/'.$marketplace->id, [
            'name' => 'New Name',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Name',
            ]);

        $this->assertDatabaseHas('marketplaces', [
            'id' => $marketplace->id,
            'name' => 'New Name',
        ]);
    }

    public function test_authenticated_user_can_delete_marketplace(): void
    {
        $user = User::factory()->create();
        $marketplace = Marketplace::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/marketplaces/'.$marketplace->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('marketplaces', [
            'id' => $marketplace->id,
        ]);
    }

    public function test_index_route_does_not_exist(): void
    {
        $user = User::factory()->create();

        // Should return 405 Method Not Allowed or 404 Not Found since it is excluded from the resource routes
        $response = $this->actingAs($user)->getJson('/marketplaces');

        $response->assertStatus(405);
    }
}
