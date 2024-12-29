<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Product;
use App\Models\Store;


class SearchService
{
    /**
     * Search for Products and Stores that match ALL specified tags and query.
     */
    public function search(array $tags, $query)
    {
        $query = trim($query);

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
