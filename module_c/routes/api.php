<?php

use App\Http\Controllers\api\ConversationController;
use App\Http\Controllers\api\ImageGenerationController;
use App\Http\Controllers\api\ImageRecognitionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::middleware('cost')->group(function () {
        Route::prefix('chat/conversation')->group(function () {
            Route::post("/", [ConversationController::class, 'StartConversation']);
            Route::post("/{conversation_id}", [ConversationController::class, 'ContinueConversation']);
            Route::get("/{conversation_id}", [ConversationController::class, 'GetPartialConversationResponse'])->withoutMiddleware('cost');
        });
        Route::prefix('imagegeneration')->group(function () {
            Route::post("/generate", [ImageGenerationController::class, 'GenerateImage']);
            Route::get("/status/{job_id}", [ImageGenerationController::class, 'GetJobStatus'])->withoutMiddleware('cost');
            Route::get("/result/{job_id}", [ImageGenerationController::class, 'GetJobResult'])->withoutMiddleware('cost');
            Route::post("/upscale", [ImageGenerationController::class, 'UpscaleImage']);
            Route::post("/zoom/in", [ImageGenerationController::class, 'ZoomInImage']);
            Route::post("/zoom/out", [ImageGenerationController::class, 'ZoomOutImage']);
        });
        Route::prefix('imagerecognition')->group(function () {
            Route::post("/recognize", [ImageRecognitionController::class, 'Recognize']);
        });
    });
});
