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

    public function store(Request $request, string $productId)
    {

        $customer = $request->user()->customer;

        // Check if the product already exists in the wishlist
        if ($customer->wishlist()->where('product_id', $productId)->exists()) {
            return $this->error('Product is already in your wishlist', 400);
        }

        // Add product to wishlist
        $customer->wishlist()->attach($productId);

        return $this->ok('Product added to wishlist');

    }

    public function destroy(Request $request, string $productId)
    {
        $customer = $request->user()->customer;

        if (! $customer->wishlist()->where('product_id', $productId)->exists()) {
            return $this->error('Product is not in your wishlist', 400);
        }

        $customer->wishlist()->detach($productId);

        return $this->ok('Product removed from wishlist');
    }
}
