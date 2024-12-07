<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Backend\OrderRepository;

class OrdersController extends Controller
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->middleware('role:Admin')->except('index', 'checkout');
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            if ($user->hasRole('Admin')) {
                $orders = Order::with(['user:id,name', 'product:id,name'])
                    ->select(['id', 'user_id', 'product_id', 'quantity', 'total_price', 'status', 'created_at'])
                    ->latest()
                    ->get();
            } elseif ($user->hasRole('User')) {
                $orders = Order::with(['user:id,name', 'product:id,name'])
                    ->select(['id', 'user_id', 'product_id', 'quantity', 'total_price', 'status', 'created_at'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            return datatables()
                ->of($orders)
                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product->name ?? 'N/A';
                })
                ->addColumn('actions', function ($row) use ($user) {
                    if ($user->hasRole('Admin')) {
                        $editUrl = route('orders.edit', $row->id);
                        $deleteUrl = route('orders.destroy', $row->id);

                        $deleteButton = $row->status != 1 ? '
                        <button type="button" class="btn btn-danger btn-sm delete-order" data-id="' . $row->id . '" data-url="' . $deleteUrl . '">Delete</button>
                    ' : '';

                        return '
                        <a href="' . $editUrl . '" class="btn btn-warning btn-sm">Edit</a>
                        ' . $deleteButton;
                    } elseif ($user->hasRole('User')) {
                        if ($row->status == 0) {
                            $checkoutUrl = route('orders.checkout', $row->id);
                            return '<a href="' . $checkoutUrl . '" class="btn btn-success btn-sm">Checkout</a>';
                        }
                    }
                    return ''; // No actions for other cases
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('orders.index');
    }

    public function create()
    {
        $users = User::role('User')->get();

        $products = Product::all();
        return view('orders.form', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:0,1', // 0 = pending, 1 = completed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order = $this->orderRepository->createOrder($request->all());

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function edit(Order $order)
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->get();
        $products = Product::all();
        return view('orders.form', compact('order', 'users', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updated = $this->orderRepository->updateOrder($id, $request->all());

        if (!$updated) {
            return redirect()->route('orders.index')->with('error', 'Order update failed.');
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $deleted = $this->orderRepository->deleteOrder($id);

        if (!$deleted) {
            return redirect()->route('orders.index')->with('error', 'Order deletion failed.');
        }

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function checkout($orderId)
    {
        $order = Order::with(['user', 'product'])->findOrFail($orderId);

        return view('orders.checkout', compact('order'));
    }
}
