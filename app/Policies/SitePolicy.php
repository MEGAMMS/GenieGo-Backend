<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;

class SitePolicy
{
    public function view(User $user, Site $site)
    {
        return $site->customer->user()->is($user);
    }

    public function update(User $user, Site $site)
    {
        return $this->view($user, $site);
    }

    public function delete(User $user, Site $site)
    {
        return $this->view($user, $site);
    }
}
