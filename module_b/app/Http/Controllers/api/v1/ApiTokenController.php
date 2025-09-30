<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    public function GetTokensByWorkspaceID(Request $request, $id)
    {
        $tokens = Token::query()->where('workspace_id', $id)->get();
        return view("token")->with(["tokens" => $tokens, "workspace_id" => $id]);
    }
    public function CreateToken(Request $request, $id)
    {
        $ws_id = $id;

        $data = $request->validate([
            "name" => "required|string|max:100"
        ]);

        $rawToken = Str::random(40);
        $hashedToken = Hash::make($rawToken);

        try {
            $token = Token::query()->create([
                "name" => $data["name"],
                "workspace_id" => $ws_id,
                "token" => $hashedToken,
            ]);
            return redirect("/token/" . $ws_id)->with(["token" => $token]);
        } catch (\Throwable $th) {
            return redirect("/token/" . $ws_id)->with(["status" => false, "message" => $th->getMessage()]);
        }
    }

    public function RevokeToken(Request $request, $id): JsonResponse {
        $token = Token::query()->find($id);

        try {
            $token->revoked_at = now();
            $token->save();
            return response()->json(["status" => true]);
        } catch (\Throwable $th) {
            return response()->json(["status" => false, "message" => $th->getMessage()]);
        }
    }
}
