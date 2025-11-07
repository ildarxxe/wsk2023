<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header_token = $request->header("X-API-TOKEN");
        if (empty($header_token)) {
           throw new AuthenticationException("Missing token.");
        }

        $token_row = ApiToken::query()->where('token', $header_token)->first();
        if (empty($token_row)) {
            throw new AuthenticationException("Token not found.");
        }

        $ws_id = $token_row->workspace_id;
        $user_id = $token_row->user_id;

        $request->merge(["workspace_id" => $ws_id, "user_id" => $user_id]);

        return $next($request);
    }
}
