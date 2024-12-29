<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Product;
use App\Models\Store;
use App\Models\Tag;

use function Laravel\Prompts\error;

class SearchService
{   
    /**
     * Search for Products and Stores that match ALL specified tags and query.
     */
    public function search(array $tags, $query)
    {
        $query = trim($query);

        // Fetch tags from the database
        $tagModels = Tag::whereIn('name', $tags)->get();

        if ($tagModels->count() !== count($tags)) {
            // If some tags are invalid, return empty results
            return ' ';
        }

        // Search Products matching all tags and query
        $productResults = Product::whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('name', $tags);
        }, '=', count($tags))
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->WhereHas('translations', function ($translationQuery) use ($query) {
                    $translationQuery->where('name', 'LIKE', "%$query%");
                });
            })
            ->with(['translations' => function ($translationQuery) use ($query) {
                $translationQuery->where('name', 'LIKE', "%$query%");
            }])
            ->get();

        // Search Stores matching all tags and query
        $storeResults = Store::whereHas('tags', function ($query) use ($tags) {
            $query->whereIn('name', $tags);
        }, '=', count($tags))
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->WhereHas('translations', function ($translationQuery) use ($query) {
                    $translationQuery->where('name', 'LIKE', "%$query%");
                });
            })
            ->with(['translations' => function ($translationQuery) use ($query) {
                $translationQuery->where('name', 'LIKE', "%$query%");
            }])
            ->get();


        return [
            'products' => ProductResource::collection($productResults),
            'stores' => StoreResource::collection($storeResults)
        ];
    }
}
