<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Random\RandomException;

class ConversationController extends Controller
{
    protected BillingService $billing_service;

    public function __construct(BillingService $billingService)
    {
        $this->billing_service = $billingService;
    }

    /**
     * @throws RandomException
     */
    public function StartConversation(Request $req): JsonResponse
    {
        $data = $req->validate([
            "prompt" => "required|string",
        ]);

        $start_time = microtime(true);

        $chatterblastResponse = [
            "id" => random_int(1, 222),
            "response" => "This is a canned response from the AI.",
            "is_final" => true
        ];

        try {
            $conversation = Conversation::query()->create([
                "user_id" => $req->user()->id,
                "workspace_id" => $req->workspace->id,
                "chatterblast_id" => $chatterblastResponse["id"],
                "is_processing" => false,
            ]);

            $end_time = microtime(true);
            $duration_ms = (int) (($end_time - $start_time) * 1000);

            $this->billing_service->LogTransaction(
                $req->user(),
                $req->workspace,
                'chatterblast',
                $duration_ms
            );

            return response()->json([
                "conversation_id" => $conversation->chatterblast_id,
                "response" => $chatterblastResponse['response'],
                "is_final" => $chatterblastResponse['is_final']
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "type" => "/problem/types/503",
                "title" => "Service Unavailable",
                "status" => 503,
                "detail" => "The service is currently unavailable."
            ], 503);
        }
    }

    public function ContinueConversation(Request $req, $conversation_id): JsonResponse{
        $data = $req->validate([
            "prompt" => "required|string",
        ]);

        $conversation = Conversation::query()->where('chatterblast_id', $conversation_id)->first();

        if (!$conversation || $conversation->user_id !== $req->user()->id) {
            return response()->json([
                "type" => "/problem/types/404",
                "title" => "Not Found",
                "status" => 404,
                "detail" => "The conversation with the provided ID was not found."
            ], 404);
        }

        if ($conversation->is_processing) {
            return response()->json([
                "type" => "/problem/types/409",
                "title" => "Conflict",
                "status" => 409,
                "detail" => "The service is already processing a request for this conversation."
            ], 409);
        }

        try {
            $start_time = microtime(true);
            $chatterblastResponse = [
                "response" => "This is a continuation of the conversation. " . $data['prompt'],
                "is_final" => true
            ];

            $conversation->is_processing = false;
            $conversation->save();

            $end_time = microtime(true);
            $duration_ms = (int) (($end_time - $start_time) * 1000);

            $this->billing_service->LogTransaction(
                $req->user(),
                $req->workspace,
                'chatterblast',
                $duration_ms
            );

            return response()->json([
                "conversation_id" => $conversation_id,
                "response" => $chatterblastResponse['response'],
                "is_final" => $chatterblastResponse['is_final']
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "type" => "/problem/types/503",
                "title" => "Service Unavailable",
                "status" => 503,
                "detail" => "The service is currently unavailable."
            ], 503);
        }
    }

    public function GetPartialConversationResponse(Request $req, $conversation_id): JsonResponse {
        $conversation = Conversation::query()->where("chatterblast_id", $conversation_id)->first();

        if (!$conversation || $conversation->user_id !== $req->user()->id) {
            return response()->json([
                "type" => "/problem/types/404",
                "title" => "Not Found",
                "status" => 404,
                "detail" => "The conversation with the provided ID was not found."
            ], 404);
        }

        if ($conversation->is_processing) {
            return response()->json([
                "status" => 'in_progress',
                "response" => "The AI is still thinking..."
            ], 200);
        } else {
            return response()->json([
                "status" => 'completed',
                "response" => "The final answer is ready."
            ], 200);
        }
    }
}
