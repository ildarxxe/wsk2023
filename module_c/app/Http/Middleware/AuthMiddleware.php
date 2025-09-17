<?php

namespace App\Http\Middleware;

use App\Models\Billing;
use App\Models\User;
use App\Models\Workspace;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
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

        if (!$token_row || $token_row->expires_at <= time()) {
            return response()->json([
                "type" => "/problem/types/401",
                "title" => "Unauthorized",
                "status" => 401,
                "detail" => "The provided X-API-TOKEN is invalid."
            ], 401);
        }

        $user = User::query()->where('id', $token_row->user_id)->first();
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
