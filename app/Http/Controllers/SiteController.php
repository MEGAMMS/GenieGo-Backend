<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SiteController extends Controller
{
    use ApiResponses;

    public function index(Request $request)
    {
        // Fetch the logged-in user's associated customer
        $customer = $request->user()->customer;

        if (! $customer) {
            return $this->error('No associated customer found for the user.', 404);
        }

        // Fetch sites related to the customer
        $sites = $customer->sites()->get();

        // Return the sites as a collection of resources
        return SiteResource::collection($sites);
    }

    public function show(Site $site)
    {
        Gate::authorize('view', $site); // Use policy

        return new SiteResource($site);
    }

    public function store(SiteRequest $request)
    {
        $site = Auth::user()->customer()->sites()->create($request->validated());

        return new SiteResource($site);
    }

    public function update(SiteRequest $request, Site $site)
    {
        $this->authorize('update', $site); // Use policy
        $site->update($request->validated());

        return new SiteResource($site);
    }

    public function destroy(Site $site)
    {
        $this->authorize('delete', $site); // Use policy
        $site->delete();

        return response()->json(['message' => 'Site deleted successfully']);
    }
}
