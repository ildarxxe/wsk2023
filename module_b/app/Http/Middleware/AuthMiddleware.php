<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $headerToken = $request->bearerToken();

        if (!$headerToken) {
            return response()->json(['message' => 'Token not provided.'], 401);
        }

        $tokenRecord = Token::query()
            ->whereNull('revoked_at')
            ->with('workspace.user')
            ->get()
            ->first(function ($token) use ($headerToken) {
                return Hash::check($headerToken, $token->token_hash);
            });

        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid or revoked token.'], 401);
        }

        $request->attributes->set('apiToken', $tokenRecord);

        if ($tokenRecord->workspace && $tokenRecord->workspace->user) {
            Auth::setUser($tokenRecord->workspace->user);
        }

        return $next($request);
    }
}
