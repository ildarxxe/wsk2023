<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Random\RandomException;

class ImageGenerationController extends Controller
{
    protected BillingService $billing_service;
    public function __construct(BillingService $billingService) {
        $this->billing_service = $billingService;
    }

    /**
     * @throws RandomException
     */
    public function GenerateImage(Request $req): JsonResponse {
        $data = $req->validate([
            "text_prompt" => "required|string",
        ]);

        $start_time = microtime(true);

        $job_id = random_int(1,222);

        try {
            $job = Job::query()->create([
                "user_id" => $req->user()->id,
                "workspace_id" => $req->workspace->id,
                "job_id" => $job_id,
                "type" => 'image_generation',
                "status" => 'pending',
                "started_at" => now(),
                "local_image_url" => null,
            ]);

            $end_time = microtime(true);
            $duration_ms = (int) (($end_time - $start_time) * 1000);

            $this->billing_service->LogTransaction(
                $req->user(),
                $req->workspace,
                'image_generation',
                $duration_ms
            );

            return response()->json([
                "job_id" => $job->job_id
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "type" => "/problem/types/503",
                "title" => "Service Unavailable",
                "status" => 503,
                "detail" => "The service is currently unavailable."
            ], 503);
        }
    }

    public function GetJobStatus(Request $req, $job_id): JsonResponse {
        $job = Job::query()->findOrFail($job_id);

        if (!$job || $job->user_id !== $req->user()->id) {
            return response()->json([
                "type" => "/problem/types/404",
                "title" => "Not Found",
                "status" => 404,
                "detail" => "The job with the provided ID was not found."
            ], 404);
        }

        $isFinished = now()->diffInSeconds($job->created_at) > 5;

        $status = $isFinished ? 'finished' : 'pending';
        $progress = $isFinished ? 100 : mt_rand(1, 99);
        $imageUrl = $isFinished ? "https://example.com/images/{$job_id}.png" : null;

        try {
            if ($isFinished && $job->status === 'pending') {
                $job->status = 'finished';
                $job->local_image_url = $imageUrl;
                $job->finished_at = now();
                $job->save();
            }
        } catch (\Throwable $th) {
            return response()->json([
                "type" => "/problem/types/503",
                "title" => "Service Unavailable",
                "status" => 503,
                "detail" => "The service is currently unavailable."
            ], 503);
        }

        return response()->json([
            "status" => $status,
            "progress" => $progress,
            "image_url" => $imageUrl,
        ], 200);
    }

    public function GetJobResult(Request $req, $job_id): JsonResponse {
        try {
            $job = Job::query()->where('job_id', $job_id)->first();

            if (!$job || $job->user_id !== $req->user()->id) {
                return response()->json([
                    "type" => "/problem/types/404",
                    "title" => "Not Found",
                    "status" => 404,
                    "detail" => "The requested resource was not found."
                ], 404);
            }

            if ($job->status !== 'finished') {
                return response()->json([
                    "type" => "/problem/types/400",
                    "title" => "Bad Request",
                    "status" => 400,
                    "detail" => "The request is invalid."
                ], 400);
            }

            return response()->json([
                "resource_id" => $job->job_id,
                "image_url" => $job->local_image_url
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

    /**
     * @throws RandomException
     */
    public function UpscaleImage(Request $req): JsonResponse
    {
        $req->validate([
            "resource_id" => "required|string",
        ]);

        $start_time = microtime(true);

        $job_id = random_int(1,222);

        $job = Job::query()->create([
            "user_id" => $req->user()->id,
            "workspace_id" => $req->workspace->id,
            "job_id" => $job_id,
            "type" => 'upscale',
            "status" => 'pending',
            "started_at" => now(),
            "local_image_url" => null,
        ]);

        $end_time = microtime(true);
        $duration_ms = (int) (($end_time - $start_time) * 1000);

        $this->billing_service->LogTransaction(
            $req->user(),
            $req->workspace,
            'upscale',
            $duration_ms
        );

        return response()->json([
            "job_id" => $job->job_id
        ], 200);
    }

    /**
     * @throws RandomException
     */
    public function ZoomInImage(Request $req): JsonResponse
    {
        $req->validate([
            "resource_id" => "required|string",
        ]);

        $start_time = microtime(true);

        $job_id = random_int(1,222);

        $job = Job::query()->create([
            "user_id" => $req->user()->id,
            "workspace_id" => $req->workspace->id,
            "job_id" => $job_id,
            "type" => 'zoom_in',
            "status" => 'pending',
            "started_at" => now(),
            "local_image_url" => null,
        ]);

        $end_time = microtime(true);
        $duration_ms = (int) (($end_time - $start_time) * 1000);

        $this->billing_service->LogTransaction(
            $req->user(),
            $req->workspace,
            'zoom_in',
            $duration_ms
        );

        return response()->json([
            "job_id" => $job->job_id
        ], 200);
    }

    /**
     * @throws RandomException
     */
    public function ZoomOutImage(Request $req): JsonResponse
    {
        $req->validate([
            "resource_id" => "required|string",
        ]);

        $start_time = microtime(true);

        $job_id = random_int(1,222);

        $job = Job::query()->create([
            "user_id" => $req->user()->id,
            "workspace_id" => $req->workspace->id,
            "job_id" => $job_id,
            "type" => 'zoom_out',
            "status" => 'pending',
            "started_at" => now(),
            "local_image_url" => null,
        ]);

        $end_time = microtime(true);
        $duration_ms = (int) (($end_time - $start_time) * 1000);

        $this->billing_service->LogTransaction(
            $req->user(),
            $req->workspace,
            'zoom_out',
            $duration_ms
        );

        return response()->json([
            "job_id" => $job->job_id
        ], 200);
    }
}
