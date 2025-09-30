<?php

use App\Http\Controllers\api\v1\ApiTokenController;
use App\Http\Controllers\api\v1\UserController;
use App\Http\Controllers\api\v1\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth")->group(function () {
    Route::get('/', function () {
        return view('login');
    })->withoutMiddleware("auth")->name("login");

    Route::post('/login', [UserController::class, "UserLogin"])->withoutMiddleware("auth");

    Route::prefix("workspace")->group(function () {
        Route::get("/", [WorkspaceController::class, "GetWorkspaces"]);
        Route::post("/create", [WorkspaceController::class, "CreateWorkspace"]);
    });

    Route::prefix("token")->group(function () {
        Route::post("/revoke/{id}", [ApiTokenController::class, "RevokeToken"]);
        Route::post("/{id}/create", [ApiTokenController::class, "CreateToken"]);
        Route::get("/{id}", [ApiTokenController::class, "GetTokensByWorkspaceID"]);
    });
});
