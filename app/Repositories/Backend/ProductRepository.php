<?php

namespace App\Repositories\Backend;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function getAllProducts(): Collection
    {
        return Product::select(['id', 'name', 'description', 'price', 'stock', 'created_at'])->get();
    }

    public function findProductById($id): ?Product
    {
        return Product::find($id);
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct($id, array $data): bool
    {
        $product = Product::findOrFail($id);
        return $product->update($data);
    }

    public function deleteProduct($id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }
}
