<?php

namespace App\Http\Controllers\API;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Order;
use App\Repositories\API\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function pay(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('product')->findOrFail($request->order_id);

        $payment = $this->paymentRepository->createPayment([
            'product_name' => $order->product->name,
            'amount' => $order->product->price * 100,
            'status' => 0,
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $order->product->name,
                        ],
                        'unit_amount' => 5000,
                        // 'unit_amount' => $order->product->price * 100,
                    ],
                    'quantity' => 1,
                    // 'quantity' => $order->quantity,
                ]],
                'mode' => 'payment',
                'success_url' => url('/api/payment-success?order_id=' . $order->id . '&payment_id=' . $payment->id),
                'cancel_url' => url('/api/payment-failure?order_id=' . $order->id . '&payment_id=' . $payment->id),
            ]);

            return response()->json(['sessionId' => $session->id], 200);
        } catch (\Exception $e) {
            \Log::error('Payment error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while processing payment'], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id');
        $paymentId = $request->query('payment_id');

        $order = Order::find($orderId);
        $payment = $this->paymentRepository->findPaymentById($paymentId);

        if (!$order || !$payment) {
            return response()->json(['error' => 'Order or payment not found'], 404);
        }

        $order->status = 1;
        $payment->status = 1;

        $order->save();
        $payment->save();

        return response()->json(['message' => 'Payment successful and status updated'], 200);
    }

    public function paymentFailure(Request $request)
    {
        $paymentId = $request->query('payment_id');

        $payment = $this->paymentRepository->findPaymentById($paymentId);

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        $this->paymentRepository->updatePaymentStatus($paymentId, 2);

        return response()->json(['message' => 'Payment failed. Please try again later.'], 500);
    }
}
