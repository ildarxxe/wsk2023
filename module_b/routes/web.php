<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\ServiceUsageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post("/login", [UserController::class, "Login"]);


Route::middleware("auth:sanctum")->group(function () {
    Route::get("/home", function () {
        return view("home");
    });
    Route::get("/tokens", [ApiTokenController::class, "viewTokens"]);
    Route::get("/tokens/create", [ApiTokenController::class, "viewCreateToken"]);

    Route::post("/tokens/{token}/revoke", [ApiTokenController::class, "revokeToken"]);
    Route::post("/tokens/create", [ApiTokenController::class, "createToken"]);

    Route::prefix("/workspaces")->group(function () {
        Route::get("/", [WorkspaceController::class, "viewWorkspaces"]);
        Route::get("/create", function () {
            return view("workspaceCreate");
        });
        Route::get("/{workspace}", [WorkspaceController::class, "viewWorkspaceByID"]);
        Route::get("/{workspace}/update", [WorkspaceController::class, "viewUpdate"]);

        Route::post("/create", [WorkspaceController::class, "createWorkspace"]);
        Route::put("/{workspace}/update", [WorkspaceController::class, "updateWorkspace"]);
    });

    Route::prefix("/bills")->group(function () {
        Route::get("/", [ServiceUsageController::class, "viewBills"]);
    });
});
