<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Backend\ProductRepository;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->middleware('role:Admin')->except('index', 'productsList');
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = $this->productRepository->getAllProducts();
            return datatables()
                ->of($products)
                ->addColumn('actions', function ($row) {
                    $editUrl = route('products.edit', $row->id);
                    $deleteUrl = route('products.destroy', $row->id);
                    return '
                    <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm delete-product" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('products.index');
    }

    public function productsList(Request $request)
    {
        if ($request->ajax()) {
            $products = $this->productRepository->getAllProducts();
            return datatables()
                ->of($products)
                ->addColumn('actions', function ($row) {
                    return '';

                    $editUrl = route('products.edit', $row->id);
                    $deleteUrl = route('products.destroy', $row->id);
                    return '
                    <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                    <button type="button" class="btn btn-danger btn-sm delete-product" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('products.index_api');
    }

    public function create()
    {
        return view('products.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $this->productRepository->createProduct($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = $this->productRepository->findProductById($id);
        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }

        return view('products.form', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $updated = $this->productRepository->updateProduct($id, $request->all());

        if (!$updated) {
            return redirect()->route('products.index')->with('error', 'Product update failed.');
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $deleted = $this->productRepository->deleteProduct($id);

        if (!$deleted) {
            return redirect()->route('products.index')->with('error', 'Product deletion failed.');
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
