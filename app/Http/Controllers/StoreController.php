<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Models\StoreTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::with('translations')->get();

        return StoreResource::collection($stores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('stores/icons', 'public');
        }

        $store = Store::create(['icon' => $iconPath]);
        // Create translations
        foreach ($request->translations as $language => $translation) {
            StoreTranslation::create([
                'store_id' => $store->id,
                'language' => $language, // Use the key as the language
                'name' => $translation['name'],
                'description' => $translation['description'] ?? null,
            ]);
        }
        $tags = $request->input('tags');
        // Attach the tag to the store
        $store->tags()->attach($tags);
        $store->save();

        $owner=Auth::user()->owner;
        $owner->addStore($store->id);
        

        return new StoreResource($store->load('translations'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = Store::with('translations')->findOrFail($id);

        return new StoreResource($store->load('translations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $store = Store::findOrFail($id);
        if ($request->hasFile('icon')) {
            if ($store->icon) {
                Storage::disk('public')->delete($store->icon); // Delete old icon
            }
            $store->icon = $request->file('icon')->store('stores/icons', 'public');
        }

        $store->save();

        // Update or create translations
        foreach ($request->translations as $translation) {
            StoreTranslation::updateOrCreate(
                [
                    'store_id' => $store->id,
                    'language' => $translation['language'],
                ],
                [
                    'name' => $translation['name'],
                    'description' => $translation['description'] ?? null,
                ]
            );
        }

        return new StoreResource($store->load('translations'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);
        if ($store->icon) {
            Storage::disk('public')->delete($store->icon);
        }

        $store->translations()->delete();
        $store->delete();

        return response()->json(['message' => 'Store deleted successfully!'], 200);
    }

    public function products(string $id)
    {
        $store = Store::with('products')->findOrFail($id);

        return ProductResource::collection($store->products);
    }

    public function addTag(Request $request,string $store_id)
    {
        $store = Store::findOrFail($store_id);

        // Assuming you are sending a 'tag_id' in the request
        $tagId = $request->input('tag_id');
    
        // Attach the tag to the store
        $store->tags()->attach($tagId);

        return response()->json(['message' => 'Tag added successfully!'], 200);
    }
}
