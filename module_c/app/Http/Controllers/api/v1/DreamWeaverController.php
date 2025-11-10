<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\BillingQuota;
use App\Models\DreamWeaver;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Random\RandomException;

class DreamWeaverController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function StartGeneration(Request $request): JsonResponse
    {
        $request->validate([
            "text_prompt" => "required|string|min:1",
        ]);

        $currentJobsExists = DreamWeaver::query()->where("status", "pending")
            ->orWhere("status", "processing")
            ->exists();

        if ($currentJobsExists) {
            throw new AuthorizationException("Current job already exists");
        }

        $serviceResponse = [
            "job_id" => Str::uuid(),
            "image_url" => "image low resolution url",
            "is_final" => false,
            "progress" => 0,
        ];

        $process = DreamWeaver::query()->create([
            "user_id" => $request->input("user_id"),
            "workspace_id" => $request->input("workspace_id"),
            "prompt" => $request->input("text_prompt"),
            "job_id" => $serviceResponse["job_id"],
            "image_url" => $serviceResponse["image_url"],
            "progress" => $serviceResponse["progress"],
        ]);

        return response()->json(["job_id" => $process->id]);
    }

    /**
     * @throws RandomException
     */
    public function GetStatusAndProgress(Request $request, $job_id): JsonResponse {
        $process = DreamWeaver::query()->firstWhere("id", $job_id);

        $currentProgress = $process->progress;
        $progress = random_int($currentProgress, 100);

        $serviceResponse = [
            "job_id" => $process->job_id,
            "is_final" => $progress === 100,
            "progress" => $progress,
            "image_url" => "other image url"
        ];

        $userResponse = [
            "status" => $process->status,
            "progress" => $process->progress,
            "image_url" => $process->image_url
        ];

        $process->update([
            "progress" => $serviceResponse["progress"],
            "image_url" => $serviceResponse["image_url"],
            "is_final" => $serviceResponse["is_final"],
            "status" => $serviceResponse["is_final"] ? "completed" : "processing",
            "finished_at" => $serviceResponse["is_final"] ? time() : null,
        ]);

        if ($serviceResponse["is_final"]) {
            $duration_ms = Carbon::createFromFormat("Y-m-d H:i:s", $process["finished_at"])->diffInMilliseconds($process["finished_at"]);
            $amount = 0.2 * $duration_ms;
            BillingQuota::query()->create([
                "user_id" => $request->input("user_id"),
                "workspace_id" => $request->input("workspace_id"),
                "service" => "dreamweaver",
                "duration_ms" => $duration_ms,
                "amount" => $amount,
            ]);
        }

        return response()->json($userResponse);
    }

    public function GetResult(Request $request, $job_id): JsonResponse {
        $process = DreamWeaver::query()->firstWhere("id", $job_id);
        if ($process->status !== "completed") {
            throw new ModelNotFoundException("Process not found");
        }

        return response()->json([
            "resource_id" => $process->id,
            "image_url" => $process->image_url
        ]);
    }

    public function Upscale(Request $request, $resource_id): JsonResponse {
        $process = DreamWeaver::query()->firstWhere("id", $resource_id);
        if (empty($process)) {
            throw new ModelNotFoundException("Process not found");
        }

        return response()->json([
            "job_id" => $process->job_id
        ]);
    }

    public function ZoomIn(Request $request, $resource_id): JsonResponse {
        $process = DreamWeaver::query()->firstWhere("id", $resource_id);
        if (empty($process)) {
            throw new ModelNotFoundException("Process not found");
        }

        return response()->json([
            "job_id" => $process->job_id
        ]);
    }

    public function ZoomOut(Request $request, $resource_id): JsonResponse {
        $process = DreamWeaver::query()->firstWhere("id", $resource_id);
        if (empty($process)) {
            throw new ModelNotFoundException("Process not found");
        }

        return response()->json([
            "job_id" => $process->job_id
        ]);
    }
}
