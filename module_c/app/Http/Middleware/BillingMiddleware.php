<?php

namespace App\Http\Middleware;

use App\Models\BillingQuota;
use App\Models\WorkspaceQuota;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BillingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ws_id = $request->input("workspace_id");

        if (!$ws_id) {
            throw new AuthorizationException('Workspace ID not found for quota check.');
        }

        $quota_row = WorkspaceQuota::query()->where('workspace_id', $ws_id)->first();
        $max_quota = $quota_row ? (float)$quota_row->max_amount : 0.0;

        $current_quota = (float)BillingQuota::query()->where('workspace_id', $ws_id)->sum("amount");

        if ($current_quota > $max_quota) {
            throw new AuthorizationException('Max spending limit for quota check.');
        }

        return $next($request);
    }
}
