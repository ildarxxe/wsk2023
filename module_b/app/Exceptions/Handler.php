<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        $this->renderable(function (ModelNotFoundException $e, $request) {
            return redirect()->back()->with("error", "Ресурс не найден.");
        });
        $this->renderable(function (ValidationException $e, $request) {
            return redirect()->back()->with("error", "Неверные переданные данные.");
        });
        $this->renderable(function (AuthorizationException $e, $request) {
            return redirect()->back()->with("error", "Нет доступа.");
        });
        $this->renderable(function (AuthenticationException $e, $request) {
            return redirect()->back()->with("error", "Необходимо авторизоваться.");
        });
        $this->renderable(function (Throwable $e, $request) {
            return redirect()->back()->with("error", "Произошла ошибка сервера.".$e->getMessage());
        });
    }
}
