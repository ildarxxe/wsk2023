<?php

namespace App\Http\Middleware;

use App\Models\Billing;
use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CalculateCostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-TOKEN');

        if (!$token) {
            return response()->json([
                "type" => "/problem/types/401",
                "title" => "Unauthorized",
                "status" => 401,
                "detail" => "The header X-API-TOKEN is missing."
            ], 401);
        }

        $token_row = DB::table("api_tokens")->where('token', $token)->first();

        if (!$token_row) {
            return response()->json([
                "type" => "/problem/types/401",
                "title" => "Unauthorized",
                "status" => 401,
                "detail" => "The provided X-API-TOKEN is invalid."
            ], 401);
        }

        $workspace = Workspace::query()->where('id', $token_row->workspace_id)->first();
        $request->merge(['workspace' => $workspace]);

        $totalSpent = Billing::query()->where('workspace_id', $workspace->id)->sum('cost');

        if ($totalSpent >= $workspace->quota) {
            return response()->json([
                "type" => "/problem/types/403",
                "title" => "Quota Exceeded",
                "status" => 403,
                "detail" => "You have exceeded your quota."
            ], 403);
        }

        return $next($request);
    }
}
