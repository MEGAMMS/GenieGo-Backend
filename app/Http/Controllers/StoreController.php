<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\UploadIconRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Models\StoreTranslation;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    use ApiResponses;

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

        $owner = $request->user()->owner;
        $owner->store_id = $store->id;
        $owner->save();

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
    public function update(Request $request)
    {
        $store = $request->user()->owner->store;

        // Update or create translations
        foreach ($request->translations as $language => $translation) {
            StoreTranslation::updateOrCreate(
                [
                    'store_id' => $store->id,
                    'language' => $language, // Use the key as the language
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
    public function destroy(Request $request)
    {
        $store = $request->user()->owner->store;
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

    public function addTag(Request $request)
    {
        $store = $request->user()->owner->store;
        // Assuming you are sending a 'tag_id' in the request
        $tagId = $request->input('tag_id');

        // Attach the tag to the store
        $store->tags()->attach($tagId);

        return response()->json(['message' => 'Tag added successfully!'], 200);
    }

    /**
     * Handle uploading the store icon.
     */
    public function uploadIcon(UploadIconRequest $request)
    {
        $store = $request->user()->owner->store;

        if ($store->icon) {
            Storage::disk('public')->delete($store->icon); // Delete old icon
        }
        // Store the uploaded file
        $icon_path = $request->file('icon')->store('store-icons', 'public');

        $store->update(['icon' => $icon_path]);

        return $this->success('Store icon uploaded successfully', ['icon_url' => asset('storage/'.$icon_path)]);

    }
}
