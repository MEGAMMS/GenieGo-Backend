<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Traits\ApiResponses;

class ProductController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('translations')->get();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // Create product
        $product = Product::create(['price' => $request->price,'stock'=>$request->stock]);

        // Create translations
        foreach ($request->translations as $translation) {
            ProductTranslation::create([
                'product_id' => $product->id,
                'language' => $translation['language'],
                'name' => $translation['name'],
                'description' => $translation['description'] ?? null,
            ]);
        }

        return new ProductResource($product->load('translations'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('translations')->findOrFail($id);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        // Find product
        $product = Product::findOrFail($id);

        // Update product price if provided
        if ($request->has('price')) {
            $product->update(['price' => $request->price]);
        }

        // Update translations
        foreach ($request->translations as $translation) {
            $productTranslation = ProductTranslation::firstOrNew([
                'product_id' => $product->id,
                'language' => $translation['language'],
            ]);
            $productTranslation->name = $translation['name'];
            $productTranslation->description = $translation['description'] ?? null;
            $productTranslation->save();
        }

        return new ProductResource($product->load('translations'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        // Delete associated translations
        $product->translations()->delete();

        // Delete the product
        $product->delete();

        return $this->ok('Product deleted successfully');
    }
}
