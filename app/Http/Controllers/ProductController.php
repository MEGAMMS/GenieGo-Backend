<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $owner = $request->user()->owner;

        // Create product
        $product = Product::create(['price' => $request->price,'stock'=>$request->stock,'store_id'=>$owner->store_id]);

        // Create translations
        foreach ($request->translations as $language => $translation) {
            ProductTranslation::create([
                'product_id' => $product->id,
                'language' => $language, // Use the key as the language
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

        Gate::authorize('update',$product);

        // Update product price if provided
        if ($request->has('price')) {
            $product->update(['price' => $request->price]);
        }

        // Update product stock if provided
        if ($request->has('stock')) {
            $product->update(['stock' => $request->stock]);
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

        Gate::authorize('delete',$product);

        // Delete associated translations
        $product->translations()->delete();

        // Delete the product
        $product->delete();

        return $this->ok('Product deleted successfully');
    }

    public function addTag(Request $request,string $product_id)
    {
        $product = Product::findOrFail($product_id);

        // Assuming you are sending a 'tag_id' in the request
        $tagId = $request->input('tag_id');
    
        // Attach the tag to the store
        $product->tags()->attach($tagId);

        return response()->json(['message' => 'Tag added successfully!'], 200);
    }
}
