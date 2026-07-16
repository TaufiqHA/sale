<?php

namespace Tests\Feature;

use App\Models\Counter;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_be_created_using_factory(): void
    {
        $counter = Counter::factory()->create(['name' => 'Counter A']);

        $customer = Customer::factory()->create([
            'counter_id' => $counter->id,
            'name' => 'John Doe',
            'phone' => '1234567890',
            'address' => '123 Main Street',
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'counter_id' => $counter->id,
            'name' => 'John Doe',
            'phone' => '1234567890',
            'address' => '123 Main Street',
        ]);
    }

    public function test_customer_belongs_to_counter(): void
    {
        $customer = Customer::factory()->create();

        $this->assertInstanceOf(Counter::class, $customer->counter);
    }

    public function test_unauthenticated_user_cannot_access_customers(): void
    {
        $this->getJson('/customers')->assertStatus(401);
    }

    public function test_authenticated_user_can_list_customers(): void
    {
        $user = User::factory()->create();
        Customer::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/customers');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_authenticated_user_can_create_customer(): void
    {
        $user = User::factory()->create();
        $counter = Counter::factory()->create();

        $response = $this->actingAs($user)->postJson('/customers', [
            'counter_id' => $counter->id,
            'name' => 'Jane Doe',
            'phone' => '0987654321',
            'address' => '456 Elm Street',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Jane Doe',
                'phone' => '0987654321',
                'address' => '456 Elm Street',
            ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Jane Doe',
            'phone' => '0987654321',
        ]);
    }

    public function test_authenticated_user_can_show_customer(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->getJson('/customers/'.$customer->id);

        $response->assertStatus(200)
            ->assertJsonPath('name', $customer->name);
    }

    public function test_authenticated_user_can_update_customer(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['name' => 'Old Name']);
        $newCounter = Counter::factory()->create();

        $response = $this->actingAs($user)->putJson('/customers/'.$customer->id, [
            'counter_id' => $newCounter->id,
            'name' => 'New Name',
            'phone' => '111222333',
            'address' => 'Updated Address',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'New Name',
                'phone' => '111222333',
                'address' => 'Updated Address',
            ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'New Name',
        ]);
    }

    public function test_authenticated_user_can_delete_customer(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($user)->deleteJson('/customers/'.$customer->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_authenticated_user_can_view_customers_index_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/customers');

        $response->assertStatus(200)
            ->assertViewIs('administrator.customer');
    }
}
