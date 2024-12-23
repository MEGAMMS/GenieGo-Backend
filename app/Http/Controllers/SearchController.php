<?php

namespace App\Http\Controllers;

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
    public function search(Request $request)
    {
        $tags = $request->input('tags', []); // Array of tags
        $query = $request->input('query');  // Search query

        if (!$query) {
            return $this->error('Query is required', 400);
        }

        $results = $this->searchService->search( $tags, $query);

        return $this->ok('search results',$results);
    }
}
