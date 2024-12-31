<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SitePolicy
{
    public function view(User $user, Site $site)
    {
        /* Log::error($site->customer()->get()); */

        return $site->customer->user === $user;
    }

    public function update(User $user, Site $site)
    {
        return $site->store->user_id === $user->id;
    }

    public function delete(User $user, Site $site)
    {
        return $site->store->user_id === $user->id;
    }
}
