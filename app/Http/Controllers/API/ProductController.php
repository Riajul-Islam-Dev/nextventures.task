<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\API\ProductRepository;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        return response()->json($this->productRepository->getAllProducts());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = $this->productRepository->createProduct($validated);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $updated = $this->productRepository->updateProduct($product, $validated);

        if (!$updated) {
            return response()->json(['message' => 'Product update failed.'], 500);
        }

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $deleted = $this->productRepository->deleteProduct($product);

        if (!$deleted) {
            return response()->json(['message' => 'Product deletion failed.'], 500);
        }

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
