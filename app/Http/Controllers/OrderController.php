<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    use ApiResponses;

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $order = Order::findOrFail($id);
        Gate::authorize('view', $order);

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = Order::where('customer_id', $user->customer->id)->get();

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $user = $request->user();

        $order = Order::create([
            'site_id' => $request->site_id,
            'customer_id' => $user->customer->id, // Authenticated user as customer
            'status' => 'pending', // Default status
            'total_price' => 0,
        ]);

        $totalPrice = 0;
        // Attach products with quantities
        foreach ($request->products as $product) {

            $productModel = Product::findOrFail($product['id']);

            // Check stock availability
            if (! $productModel->inStock($product['quantity'])) {
                return $this->error('Insufficient stock.', 400);
            }

            $productModel->reduceStock($product['quantity']);

            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
            $order->save();
            $totalPrice += $productModel->price * $product['quantity'];
        }
        $order->total_price = $totalPrice;
        $order->save();

        return new OrderResource($order);
    }

    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('update', $order);
        $order->status = $request->status;
        $order->save();

        return $this->ok('status has been changed');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        Gate::authorize('delete', $order);

        foreach ($order->products as $product) {
            $productModel = Product::findOrFail($product['id']);
            $productModel->increaseStock((int) $product['quantity']);
        }
        $order->delete();

        return $this->ok('Order deleted successfully');
    }
}
