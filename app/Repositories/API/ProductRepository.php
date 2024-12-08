<?php

namespace App\Repositories\API;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function getAllProducts(): Collection
    {
        return Product::all();
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function findProductById($id): ?Product
    {
        return Product::find($id);
    }

    public function updateProduct(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function deleteProduct(Product $product): bool
    {
        return $product->delete();
    }
}
