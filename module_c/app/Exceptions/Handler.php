<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (ValidationException $e, Request $request) {
            if($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    "type" => "/problem/types/400",
                    "title" => "Bad Request",
                    "status" => 400,
                    "detail" => "The request is invalid."
                ], 400);
            }
            return null;
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    "type" => "/problem/types/401",
                    "title" => "Unauthorized",
                    "status" => 401,
                    "detail" => "The header X-API-TOKEN is missing or invalid."
                ], 401);
            }
            return null;
        });

        $this->renderable(function (AuthorizationException $e, Request $request) {
            if($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    "type" => "/problem/types/403",
                    "title" => "Quota Exceeded",
                    "status" => 403,
                    "detail" => "You have exceeded your quota."
                ], 403);
            }
            return null;
        });

        $this->renderable(function (ModelNotFoundException $e, Request $request) {
            if($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    "type" => "/problem/types/404",
                    "title" => "Resource Not Found",
                    "status" => 404,
                    "detail" => "Resource not found."
                ], 404);
            }
            return null;
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if($request->wantsJson() || $request->expectsJson()) {
                return response()->json([
                    "type" => "/problem/types/503",
                    "title" => "Service Unavailable",
                    "status" => 503,
                    "detail" => "Server internal error."
                ], 503);
            }
            return null;
        });
    }
}
