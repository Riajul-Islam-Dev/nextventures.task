<?php

namespace App\Http\Controllers\API;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('product')->findOrFail($request->order_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $order->product->name,
                        ],
                        // 'unit_amount' => $order->product->price * 100,
                        'unit_amount' => 5000,
                    ],
                    // 'quantity' => $order->quantity,
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => url('/payment-success'),
                'cancel_url' => url('/payment-failure'),
            ]);

            return response()->json(['sessionId' => $session->id], 200);
        } catch (\Exception $e) {
            \Log::error('Payment error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while processing payment'], 500);
        }
    }

    public function paymentSuccess()
    {
        return response()->json(['message' => 'Payment was successful. Thank you for your purchase!'], 200);
    }

    public function paymentFailure()
    {
        return response()->json(['message' => 'Payment failed. Please try again later.'], 500);
    }
}
