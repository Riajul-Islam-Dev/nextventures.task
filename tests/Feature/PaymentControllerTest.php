<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentControllerTest extends TestCase
{
    use DatabaseTransactions;

    private function createTestUser()
    {
        return User::factory()->create([
            'email' => 'user_' . Str::uuid() . '@example.com',
        ]);
    }

    public function test_place_order_success()
    {
        $user = $this->createTestUser();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'product_id' => 1,
            'quantity' => 2,
            'total_price' => 2000,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/pay', ['order_id' => $order->id]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['sessionId']);
    }

    public function test_payment_success()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'status' => 1,
            'product_id' => Product::factory()->create()->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/pay', ['order_id' => $order->id]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['sessionId']);

        $this->assertDatabaseHas('payments', [
            'product_name' => $order->product->name,
            'amount' => $order->product->price * 100,
            'status' => 0,
        ]);
    }

    public function test_payment_failure()
    {
        $user = $this->createTestUser();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'product_id' => 1,
            'quantity' => 1,
            'total_price' => 1000,
        ]);

        $payment = \App\Models\Payment::factory()->create([
            'status' => 0,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/payment-failure?payment_id=' . $payment->id);

        $response->assertStatus(500);
        $response->assertJson(['message' => 'Payment failed. Please try again later.']);

        $payment->refresh();
        $this->assertEquals(2, $payment->status);
    }

    // php artisan test --filter PaymentControllerTest
}
