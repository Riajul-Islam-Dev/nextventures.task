<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $result = $this->orderRepository->createOrder($user, $request->product_id, $request->quantity);

        if (is_array($result) && isset($result['error'])) {
            return response()->json(['message' => $result['error']], 400);
        }

        return response()->json(['message' => 'Order placed successfully!', 'order' => $result], 201);
    }

    public function fetchUserOrders()
    {
        $user = Auth::user();
        $orders = $this->orderRepository->getOrdersByUser($user);

        return response()->json(['orders' => $orders], 200);
    }
}
