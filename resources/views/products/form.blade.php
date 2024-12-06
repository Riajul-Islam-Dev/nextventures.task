@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ isset($product) ? 'Edit Product' : 'Create Product' }}</h2>
        <form action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}"
            method="POST">
            @csrf
            @if (isset($product))
                @method('PUT')
            @endif

            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}"
                    required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" required>{{ old('description', $product->description ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $product->price ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? '') }}"
                    required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>
@endsection
