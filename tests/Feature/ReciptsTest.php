<?php

namespace Tests\Feature;

use App\Models\Recipts;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReciptsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_recipts(): void
    {
        $this->getJson('/recipts')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_recipts(): void
    {
        $user = User::factory()->create();
        Recipts::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/recipts');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_create_recipt(): void
    {
        $user = User::factory()->create();
        $sale = Sale::factory()->create();

        $response = $this->actingAs($user)->postJson('/recipts', [
            'sales_id' => $sale->id,
            'receipt_number' => 'RCP-00100',
            'type' => 'umum',
            'printed_count' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'sales_id' => $sale->id,
                'receipt_number' => 'RCP-00100',
                'type' => 'umum',
                'printed_count' => 1,
            ]);

        $this->assertDatabaseHas('recipts', [
            'sales_id' => $sale->id,
            'receipt_number' => 'RCP-00100',
        ]);
    }

    public function test_create_recipt_validation_requires_unique_sales_id_and_receipt_number(): void
    {
        $user = User::factory()->create();
        $existingRecipt = Recipts::factory()->create();

        $response = $this->actingAs($user)->postJson('/recipts', [
            'sales_id' => $existingRecipt->sales_id,
            'receipt_number' => $existingRecipt->receipt_number,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sales_id', 'receipt_number']);
    }

    public function test_authenticated_user_can_show_recipt(): void
    {
        $user = User::factory()->create();
        $recipt = Recipts::factory()->create();

        $response = $this->actingAs($user)->getJson('/recipts/'.$recipt->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $recipt->id,
                'receipt_number' => $recipt->receipt_number,
            ]);
    }

    public function test_authenticated_user_can_update_recipt(): void
    {
        $user = User::factory()->create();
        $recipt = Recipts::factory()->create(['printed_count' => 0]);

        $response = $this->actingAs($user)->putJson('/recipts/'.$recipt->id, [
            'sales_id' => $recipt->sales_id,
            'receipt_number' => 'RCP-UPDATED',
            'type' => 'marketplace',
            'printed_count' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'receipt_number' => 'RCP-UPDATED',
                'printed_count' => 2,
                'type' => 'marketplace',
            ]);

        $this->assertDatabaseHas('recipts', [
            'id' => $recipt->id,
            'receipt_number' => 'RCP-UPDATED',
            'printed_count' => 2,
        ]);
    }

    public function test_authenticated_user_can_delete_recipt(): void
    {
        $user = User::factory()->create();
        $recipt = Recipts::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/recipts/'.$recipt->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('recipts', [
            'id' => $recipt->id,
        ]);
    }

    public function test_authenticated_user_can_bulk_print_recipts(): void
    {
        $user = User::factory()->create();
        $recipts = Recipts::factory()->count(3)->create(['printed_count' => 0]);

        $ids = $recipts->pluck('id')->toArray();

        $response = $this->actingAs($user)->postJson('/recipts/bulk-print', [
            'ids' => $ids,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'count' => 3,
            ]);

        foreach ($ids as $id) {
            $this->assertDatabaseHas('recipts', [
                'id' => $id,
                'printed_count' => 1,
            ]);
        }
    }
}
