<?php

namespace App\Repositories\Backend;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function getAllOrders()
    {
        return Order::with(['user', 'product'])->latest()->get();
    }

    public function findOrderById($id)
    {
        return Order::with(['user', 'product'])->find($id);
    }

    public function createOrder(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::findOrFail($data['product_id']);

            if ($product->stock < $data['quantity']) {
                throw new \Exception('Insufficient stock for the selected product.');
            }

            $totalPrice = $product->price * $data['quantity'];

            $order = Order::create([
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'total_price' => $totalPrice,
                'status' => $data['status'],
            ]);

            $product->decrement('stock', $data['quantity']);

            return $order;
        });
    }

    public function updateOrder($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $order = Order::findOrFail($id);
            $product = Product::findOrFail($data['product_id']);

            if ($product->stock + $order->quantity < $data['quantity']) {
                throw new \Exception('Insufficient stock for the selected product.');
            }

            // Adjust stock
            $product->increment('stock', $order->quantity); // Add back the original quantity
            $product->decrement('stock', $data['quantity']); // Deduct the new quantity

            $totalPrice = $product->price * $data['quantity'];

            $order->update([
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
                'quantity' => $data['quantity'],
                'total_price' => $totalPrice,
                'status' => $data['status'],
            ]);

            return $order;
        });
    }

    public function deleteOrder($id)
    {
        return DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);

            $product = Product::find($order->product_id);
            if ($product) {
                $product->increment('stock', $order->quantity);
            }

            return $order->delete();
        });
    }
}
