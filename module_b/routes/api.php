<?php

use App\Http\Controllers\api\v1\BillController;
use App\Http\Controllers\api\v1\BillingQuotaController;
use App\Http\Controllers\api\v1\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix("workspace")->group(function () {
            Route::post("/{id}", [WorkspaceController::class, "UpdateWorkspace"]);
        });

        Route::prefix("billing-quota")->group(function () {
            Route::get("/status/{id}", [BillingQuotaController::class, "GetQuotaStatus"]);
            Route::post("/set/{id}", [BillingQuotaController::class, "SetQuota"]);
            Route::post("/delete/{id}", [BillingQuotaController::class, "DeleteQuota"]);
        });

        Route::prefix("bills")->group(function () {
            Route::get("/workspace/{id}", [BillController::class, "GetBillsByWorkspaceID"]);
            Route::get("/{id}", [BillController::class, "GetBillDetails"]);
        });
    });
});
