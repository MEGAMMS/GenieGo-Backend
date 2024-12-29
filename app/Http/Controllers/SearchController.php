<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Tag;
use App\Services\SearchService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class SearchController extends Controller
{
    use ApiResponses;

    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Search across products and stores with tag and translation support.
     */
    public function search(SearchRequest $request)
    {
        $tags = $request->input('tags', []); // Array of tags
        $query = $request->input('query');  // Search query

        // Fetch tags from the database
        $tagModels = Tag::whereIn('name', $tags)->get();

        if ($tagModels->count() !== count($tags)) {
            // If some tags are invalid, return empty results
            return $this->error('Invalid tags', 400);
        }

        $results = $this->searchService->search($tags, $query);

        return $this->ok('search results', $results);
    }
}
