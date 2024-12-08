<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $adminUser;
    private $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('Admin');

        $this->product = Product::factory()->create([
            'stock' => 100,
            'price' => 50.00,
        ]);
    }

    public function test_place_order_success()
    {
        $orderData = [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/orders', $orderData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Order placed successfully!',
            ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->adminUser->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);

        $this->product->refresh();
        $this->assertEquals(98, $this->product->stock);
    }

    public function test_place_order_insufficient_stock()
    {
        $orderData = [
            'product_id' => $this->product->id,
            'quantity' => 200,
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/orders', $orderData);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Insufficient stock',
            ]);
    }

    public function test_place_order_product_not_found()
    {
        $orderData = [
            'product_id' => 9999,
            'quantity' => 1,
        ];

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->postJson('/api/orders', $orderData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('product_id');
    }

    public function test_fetch_user_orders()
    {
        $order1 = Order::create([
            'user_id' => $this->adminUser->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'total_price' => $this->product->price * 2,
            'status' => 0,
        ]);

        $order2 = Order::create([
            'user_id' => $this->adminUser->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'total_price' => $this->product->price * 1,
            'status' => 0,
        ]);

        $response = $this->actingAs($this->adminUser, 'sanctum')
            ->getJson('/api/orders');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'orders');
    }

    public function test_unauthorized_access_to_place_order()
    {
        $orderData = [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ];

        $response = $this->postJson('/api/orders', $orderData);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }

    // php artisan test --filter OrderControllerTest
}
