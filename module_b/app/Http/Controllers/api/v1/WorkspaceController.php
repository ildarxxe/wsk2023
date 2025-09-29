<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function GetWorkspaces(Request $request): JsonResponse {
        $ws = Workspace::query()->where("user_id", $request->user()->id)->get();
        return response()->json(["status" => true, "workspaces" => $ws]);
    }
    public function CreateWorkspace(Request $request): JsonResponse
    {
        $data = $request->validate([
            "title" => "required",
            "description" => "nullable|string",
        ]);

        try {
            Workspace::query()->create([
                "user_id" => $request->user()->id,
                "title" => $data["title"],
                "description" => $data["description"] ?? null,
            ]);
            return response()->json(["status" => true]);
        } catch (\Throwable $th) {
            return response()->json(["status" => false, "message" => $th->getMessage()]);
        }
    }

    public function UpdateWorkspace(Request $request, $id): JsonResponse
    {
        $data = $request->validate([
            "title" => "required",
            "description" => "nullable|string",
        ]);

        try {
            $ws = Workspace::query()->find($id);
            $ws->update($data);
            $ws->save();
            return response()->json(["status" => true]);
        } catch (\Throwable $th) {
            return response()->json(["status" => false, "message" => $th->getMessage()]);
        }
    }
}
