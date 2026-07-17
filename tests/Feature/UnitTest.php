<?php

namespace Tests\Feature;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_unit_can_be_created_using_factory(): void
    {
        $unit = Unit::factory()->create([
            'name' => 'Pieces',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' => 'Pieces',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_units(): void
    {
        $this->getJson('/units')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_units(): void
    {
        $user = User::factory()->create();
        Unit::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/units');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_create_unit(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/units', [
            'name' => 'Boxes',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Boxes',
            ]);

        $this->assertDatabaseHas('units', [
            'name' => 'Boxes',
        ]);
    }

    public function test_authenticated_user_can_show_unit(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create();

        $response = $this->actingAs($user)->getJson('/units/'.$unit->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $unit->id,
                'name' => $unit->name,
            ]);
    }

    public function test_authenticated_user_can_update_unit(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create(['name' => 'Old Unit']);

        $response = $this->actingAs($user)->putJson('/units/'.$unit->id, [
            'name' => 'New Unit',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Unit',
            ]);

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' => 'New Unit',
        ]);
    }

    public function test_authenticated_user_can_delete_unit(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/units/'.$unit->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('units', [
            'id' => $unit->id,
        ]);
    }
}
