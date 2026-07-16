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
            'code' => 'PCS',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' => 'Pieces',
            'code' => 'PCS',
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
            'code' => 'BOX',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Boxes',
                'code' => 'BOX',
            ]);

        $this->assertDatabaseHas('units', [
            'name' => 'Boxes',
            'code' => 'BOX',
        ]);
    }

    public function test_create_unit_validation_requires_unique_code(): void
    {
        $user = User::factory()->create();
        Unit::factory()->create(['code' => 'PCS']);

        $response = $this->actingAs($user)->postJson('/units', [
            'name' => 'Pieces Duplicate',
            'code' => 'PCS',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
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
                'code' => $unit->code,
            ]);
    }

    public function test_authenticated_user_can_update_unit(): void
    {
        $user = User::factory()->create();
        $unit = Unit::factory()->create(['name' => 'Old Unit', 'code' => 'OLD']);

        $response = $this->actingAs($user)->putJson('/units/'.$unit->id, [
            'name' => 'New Unit',
            'code' => 'NEW',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Unit',
                'code' => 'NEW',
            ]);

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' => 'New Unit',
            'code' => 'NEW',
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
