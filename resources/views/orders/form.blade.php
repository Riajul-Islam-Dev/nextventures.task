@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ isset($order) ? 'Edit Order' : 'Create Order' }}
                    </div>

                    <div class="card-body">
                        <form method="POST"
                            action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}">
                            @csrf
                            @if (isset($order))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="user_id" class="form-label">User</label>
                                <select class="form-control @error('user_id') is-invalid @enderror" id="user_id"
                                    name="user_id">
                                    <option value="">Select a User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ isset($order) && $order->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="product_id" class="form-label">Product</label>
                                <select class="form-control @error('product_id') is-invalid @enderror" id="product_id"
                                    name="product_id">
                                    <option value="">Select a Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ isset($order) && $order->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stock: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                    id="quantity" name="quantity"
                                    value="{{ old('quantity', isset($order) ? $order->quantity : '') }}" min="1">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status"
                                    name="status">
                                    <option value="0" {{ isset($order) && $order->status == 0 ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="1" {{ isset($order) && $order->status == 1 ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ isset($order) ? 'Update Order' : 'Create Order' }}
                            </button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
