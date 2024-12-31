<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    use ApiResponses;
    /**
     * Display the specified resource.
     */
    public function index(Request $request)
    {   
        $user = $request->user();

        $wishlist = $user->customer->wishlist()->get();

        return ProductResource::collection($wishlist);
    }

    public function store(Request $request,$productId)
    {

        $customer = $request->user()->customer;

        // Check if the product already exists in the wishlist
        if ($customer->customer->wishlist()->where('product_id', $productId)->exists()) {
            return $this->error('Product is already in your wishlist', 400);
        }

        // Add product to wishlist
        $customer->wishlist()->attach($productId);

        return $this->ok('Product added to wishlist');
        
    }
}
