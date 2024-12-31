<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
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
        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function index(Request $request)
    {   
        $user = $request->user();
        $orders = Order::where('user_id',$user->id)->get();
        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $user = $request->user(); 

        Gate::authorize('create', Order::class);

        $order = Order::create([
            'store_id' => $request->store_id,
            'customer_id' => 1, // Authenticated user as customer
            'status' => 'pending', // Default status
        ]);

        $totalPrice=0;
        // Attach products with quantities
        foreach ($request->products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
            $order->save();
            $totalPrice+=$product['price'] * $product['quantity'];
        }
        $order->total_price=$totalPrice;
        $order->save();

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
