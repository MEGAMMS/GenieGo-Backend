<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponses;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponses;
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function Orders(Request $request)
    {   
        $user = $request->user();
        $orders = Order::where('user_id',$user->id);
        return new OrderResource($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $order = Order::create([
            'store_id' => $request->store_id,
            'customer_id' => $request->user()->id, // Authenticated user as customer
            'status' => 'pending', // Default status
        ]);

        $totalPrice=0;
        // Attach products with quantities
        foreach ($request->products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
            $totalPrice=$totalPrice+$product->price;
        }
        $order->total_price=$totalPrice;

        return new OrderResource($order);
    }

    public function update( string $id)
    {
        $order = Order::findOrFail($id);
        return response()->json(['status'=>$order->status]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return $this->ok('Order deleted successfully');
    }
}
