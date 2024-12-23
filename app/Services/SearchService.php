<?php 

namespace App\Services;

use App\Models\Product;
use App\Models\Store;
use App\Models\Tag;

class SearchService
{
     /**
     * Search for Products and Stores that match ALL specified tags and query.
     */
    public function search(array $tags, $query)
    {
        $results = collect();

        $query = trim($query);

        if (!empty($tags)) {
            // Fetch tags from the database
            $tagModels = Tag::whereIn('name', $tags)->get();

            if ($tagModels->count() !== count($tags)) {
                // If some tags are invalid, return empty results
                return $results;
            }

            // Search Products matching all tags and query
            $productResults = Product::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            }, '=', count($tags))
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->WhereHas('translations', function ($translationQuery) use ($query) {
                                 $translationQuery->where('name', $query); // Exact match with Translation name
                             });
            })
            ->with(['translations' => function ($translationQuery) use ($query) {
                $translationQuery->where('name', $query); // Load only the matching translation
            }])
            ->get();
        
            $results = $results->merge($productResults);

            // Search Stores matching all tags and query
            $storeResults = Store::whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('name', $tags);
            }, '=', count($tags))
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->WhereHas('translations', function ($translationQuery) use ($query) {
                                 $translationQuery->where('name', $query); // Exact match with Translation name
                             });
            })
            ->with(['translations' => function ($translationQuery) use ($query) {
                $translationQuery->where('name', $query); // Load only the matching translation
            }])
            ->get();
        
            $results = $results->merge($storeResults);
        }

        return $results;
    }


    public function suggest($type, $query)
    {
        if (!$query) {
            return [];
        }

        $query = trim($query);

        if ($type === 'product') {
            return Product::where('name', 'LIKE', "%$query%")
                          ->take(5)
                          ->pluck('name');
        } elseif ($type === 'store') {
            return Store::where('name', 'LIKE', "%$query%")
                        ->take(5)
                        ->pluck('name');
        }

        return [];
    }
}
