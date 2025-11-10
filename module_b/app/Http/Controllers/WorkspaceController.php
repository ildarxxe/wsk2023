<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\ServiceUsage;
use App\Models\Workspace;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function viewWorkspaces(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $workspaces = Workspace::query()->where("user_id", $request->user()->id)->get();
        return view("workspaces")->with("workspaces", $workspaces);
    }

    public function createWorkspace(Request $request): \Illuminate\Foundation\Application|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            "title" => "required|string|max:100",
            "description" => "nullable|string",
        ]);

        Workspace::query()->create([
            "user_id" => $request->user()->id,
            "title" => $request->input("title"),
            "description" => $request->input("description"),
        ]);

        return redirect("/workspaces/create")->with("message", "Рабочее пространство успешно создано!");
    }

    public function viewUpdate(Request $request, Workspace $workspace): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view("workspaceUpdate")->with("workspace", $workspace);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateWorkspace(Request $request, Workspace $workspace): \Illuminate\Http\RedirectResponse
    {
        $this->authorize("update", $workspace);
        $request->validate([
            "title" => "required|string|max:100",
            "description" => "nullable|string",
        ]);

        $workspace->update([
            "title" => $request->input("title"),
            "description" => $request->input("description"),
        ]);

        return redirect()->back()->with("message", "Рабочее пространство успешно обновлено!");
    }

    public function viewWorkspaceByID(Request $request, Workspace $workspace): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $tokens = ApiToken::query()->where("workspace_id", $workspace->id)->get();
        $bills = ServiceUsage::query()->where("username", $request->user()->name)->get();
        return view("workspace")->with(["workspace" => $workspace, "tokens" => $tokens, "bills" => $bills]);
    }
}
