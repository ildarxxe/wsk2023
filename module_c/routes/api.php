<?php

use app\Http\Controllers\api\v1\ConversationController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth")->group(function () {
    Route::middleware("quota")->group(function () {
        Route::prefix("chat/conversation")->group(function () {
            Route::post("/", [ConversationController::class, "CreateConversation"]);
            Route::put("/{conversation_id}", [ConversationController::class, "ContinueConversation"]);
            Route::get("/{conversation_id}", [ConversationController::class, "GetPartialAnswer"]);
        });
    });
});
