<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Product;

class OrderRepository
{
    public function createOrder($user, $productId, $quantity)
    {
        $product = Product::find($productId);

        if (!$product) {
            return ['error' => 'Product not found'];
        }

        if ($product->stock < $quantity) {
            return ['error' => 'Insufficient stock'];
        }

        $totalPrice = $product->price * $quantity;
        $order = Order::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'status' => 0, // 0 means 'pending'
        ]);

        // Update the product stock
        $product->stock -= $quantity;
        $product->save();

        return $order;
    }

    public function getOrdersByUser($user)
    {
        return Order::where('user_id', $user->id)->get();
    }
}
