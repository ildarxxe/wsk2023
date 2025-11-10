<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    public function viewTokens(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $workspaces = Workspace::query()->with("tokens")->where("user_id", $request->user()->id)->get();

        $tokens = $workspaces->flatMap(fn ($workspace) => $workspace->tokens);

        return view("tokens")->with("tokens", $tokens);
    }

    /**
     * @throws AuthorizationException
     */
    public function revokeToken(Request $request, ApiToken $token): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $this->authorize("revokeToken", $token);
        $token->update([
            "revoked_at" => Carbon::now()
        ]);

        return redirect("/tokens")->with("message", "Токен отозван.");
    }

    public function viewCreateToken(Request $request) {
        $workspaces = Workspace::query()->where("user_id", $request->user()->id)->get();
        return view("tokenCreate")->with("workspaces", $workspaces);
    }

    public function createToken(Request $request) {
        $request->validate([
            "name" => "required|string|max:100",
            "workspace_id" => "required|string",
        ]);

        $token = Str::random(60);
        ApiToken::query()->create([
            "name" => $request->input("name"),
            "workspace_id" => $request->input("workspace_id"),
            "token" => $token,
        ]);

        return redirect("/tokens/create")->with(["message" => "Токен успешно создан, токен: $token"]);
    }
}
