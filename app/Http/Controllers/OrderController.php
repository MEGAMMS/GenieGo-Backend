<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
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
}
