<?php

namespace app\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\BillingQuota;
use App\Models\Conversation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ConversationController extends Controller
{
    public function CreateConversation(Request $request): JsonResponse
    {
        $request->validate([
            "prompt" => "required|string|min:1",
        ]);

        $serviceResponse = [
            "conversation_id" => Str::uuid()->toString(),
            "answer" => "part answer",
            "is_final" => false,
        ];

        $conversation = Conversation::query()->create([
            "user_id" => $request->input("user_id"),
            "workspace_id" => $request->input("workspace_id"),
            "chatterblast_conversation_id" => $serviceResponse["conversation_id"],
            "question" => $request->get("prompt"),
            "answer" => $serviceResponse["answer"],
        ]);

        return response()->json([
            "conversation_id" => $conversation['id'],
            "response" => $serviceResponse["answer"],
            "is_final" => $serviceResponse["is_final"],
        ]);
    }

    public function GetPartialAnswer(Request $req, string $conversation_id): JsonResponse {
        $conversation = Conversation::query()->where("id", $conversation_id)->first();
        $response = Gate::inspect('getPartialAnswer', $conversation);

        if (!$response->allowed()) {
            throw new AuthorizationException::class;
        }

        if (empty($conversation)) {
            throw new ModelNotFoundException("Conversation not found.");
        }

        if ($conversation->status !== "completed") {
            $serviceResponse = [
                "conversation_id" => $conversation->chatterblast_conversation_id,
                "answer" => "part answer 2",
                "is_final" => (bool)rand(0,1),
            ];

            $conversation->update([
                "answer" => $serviceResponse["answer"],
                "status" => $serviceResponse["is_final"] ? "completed" : "processing",
            ]);

            if ($serviceResponse["is_final"]) {
                $duration_ms = rand(0, 1000);
                BillingQuota::query()->create([
                    "user_id" => $req->input("user_id"),
                    "workspace_id" => $req->input("workspace_id"),
                    "service" => "chatterblast",
                    "duration_ms" => $duration_ms,
                    "amount" => 0.2 * $duration_ms,
                ]);
            }

            return response()->json([
                'conversation_id' => $serviceResponse["conversation_id"],
                "response" => $serviceResponse["answer"],
                "is_final" => $serviceResponse["is_final"],
            ]);
        }
        return response()->json([
            "type" => "/problem/types/403",
            "title" => "Forbidden",
            "status" => 403,
            "detail" => "Conversation is completed."
        ]);
    }

    public function ContinueConversation(Request $request, string $conversation_id): JsonResponse {
        $conversation = Conversation::query()->where("id", $conversation_id)->first();
        $response = Gate::inspect('continueChat', $conversation);

        if (!$response->allowed()) {
            throw new AuthorizationException::class;
        }

        $request->validate([
            "prompt" => "required|string|min:1",
        ]);


        if (empty($conversation)) {
            throw new ModelNotFoundException("Conversation not found.");
        }

        if ($conversation->status === "completed") {
            $serviceResponse = [
                "conversation_id" => $conversation->chatterblast_conversation_id,
                "answer" => "part answer",
                "is_final" => false,
            ];

            $conversation = Conversation::query()->create([
                "user_id" => $request->input("user_id"),
                "workspace_id" => $request->input("workspace_id"),
                "chatterblast_conversation_id" => $serviceResponse["conversation_id"],
                "question" => $request->get("prompt"),
                "answer" => $serviceResponse["answer"],
            ]);

            return response()->json([
                "conversation_id" => $conversation['id'],
                "response" => $serviceResponse["answer"],
                "is_final" => $serviceResponse["is_final"],
            ]);
        }
        return response()->json([
            "type" => "/problem/types/403",
            "title" => "Forbidden",
            "status" => 403,
            "detail" => "Conversation is not completed."
        ]);
    }
}
