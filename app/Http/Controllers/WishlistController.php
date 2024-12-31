<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function index(Request $request)
    {   
        $user = $request->user();

        $wishlist = $user->customer->wishlist()->get();

        return ProductResource::collection($wishlist);
    }
}
