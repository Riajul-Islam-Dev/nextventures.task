@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h2>Checkout</h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h5 class="text-muted">Order Summary</h5>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>Order ID:</strong></span>
                            <span>#{{ $order->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>Product:</strong></span>
                            <span>{{ $order->product->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>Quantity:</strong></span>
                            <span>{{ $order->quantity }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>Total Price:</strong></span>
                            <span class="text-success">${{ $order->total_price }}</span>
                        </div>
                        <hr>
                        <div class="text-center">
                            <button id="checkoutButton" class="btn btn-lg btn-success">
                                <i class="fas fa-credit-card"></i> Pay with Stripe
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ config('services.stripe.key') }}');

        document.getElementById('checkoutButton').addEventListener('click', function() {
            fetch('/api/pay', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        order_id: {{ $order->id }}
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.sessionId) {
                        stripe.redirectToCheckout({
                            sessionId: data.sessionId
                        });
                    } else {
                        alert('Failed to initiate payment.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing your payment.');
                });
        });
    </script>
@endpush
