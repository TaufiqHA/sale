<?php

namespace Tests\Feature;

use App\Models\Invoices;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_invoices(): void
    {
        $this->getJson('/invoices')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_invoices(): void
    {
        $user = User::factory()->create();
        Invoices::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/invoices');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_create_invoice(): void
    {
        $user = User::factory()->create();
        $sale = Sale::factory()->create();

        $response = $this->actingAs($user)->postJson('/invoices', [
            'sales_id' => $sale->id,
            'invoice_number' => 'INV-00100',
            'type' => 'umum',
            'printed_count' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'sales_id' => $sale->id,
                'invoice_number' => 'INV-00100',
                'type' => 'umum',
                'printed_count' => 1,
            ]);

        $this->assertDatabaseHas('invoices', [
            'sales_id' => $sale->id,
            'invoice_number' => 'INV-00100',
        ]);
    }

    public function test_create_invoice_validation_requires_unique_sales_id_and_invoice_number(): void
    {
        $user = User::factory()->create();
        $existingInvoice = Invoices::factory()->create();
        $newSale = Sale::factory()->create();

        $response = $this->actingAs($user)->postJson('/invoices', [
            'sales_id' => $existingInvoice->sales_id,
            'invoice_number' => $existingInvoice->invoice_number,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sales_id', 'invoice_number']);
    }

    public function test_authenticated_user_can_show_invoice(): void
    {
        $user = User::factory()->create();
        $invoice = Invoices::factory()->create();

        $response = $this->actingAs($user)->getJson('/invoices/'.$invoice->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
            ]);
    }

    public function test_authenticated_user_can_update_invoice(): void
    {
        $user = User::factory()->create();
        $invoice = Invoices::factory()->create(['printed_count' => 0]);

        $response = $this->actingAs($user)->putJson('/invoices/'.$invoice->id, [
            'sales_id' => $invoice->sales_id,
            'invoice_number' => 'INV-UPDATED',
            'type' => 'umum',
            'printed_count' => 2,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'invoice_number' => 'INV-UPDATED',
                'printed_count' => 2,
            ]);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'invoice_number' => 'INV-UPDATED',
            'printed_count' => 2,
        ]);
    }

    public function test_authenticated_user_can_delete_invoice(): void
    {
        $user = User::factory()->create();
        $invoice = Invoices::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/invoices/'.$invoice->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
    }
}
