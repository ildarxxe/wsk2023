<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class UserController extends Controller
{
    public function UserLogin(Request $request): JsonResponse
    {
        $data = $request->validate([
            "username" => "required|string",
            "password" => "required|string"
        ]);

        $user = User::query()->where("username", $data["username"])->first();
        if (!$user || !Hash::check($data["password"], $user->password)) {
            return response()->json(["message" => "Wrong data"], 401);
        }

        $token = $user->createToken('api-token', ['*'])->plainTextToken;

        return response()->json(["status" => true, "token" => $token, "user" => $user]);
    }
}
