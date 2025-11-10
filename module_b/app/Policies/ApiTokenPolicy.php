<?php

namespace App\Policies;

use App\Models\ApiToken;
use App\Models\User;
use App\Models\Workspace;

class ApiTokenPolicy
{
    public function revokeToken(User $user, ApiToken $apiToken): bool {
        $ws = Workspace::query()->where("id", $apiToken->workspace_id)->first();
        return $user->id === $ws->user_id;
    }
}
