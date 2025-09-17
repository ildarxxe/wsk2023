<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageRecognitionController extends Controller
{
    protected BillingService $billingService;
    public function __construct(BillingService $billingService) {
        $this->billingService = $billingService;
    }
    public function Recognize(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $start_time = microtime(true);

            $aiResponse = [
                "objects" => [
                    [
                        "name" => "car",
                        "probability" => 0.95,
                        "bounding_box" => [
                            "x" => 100,
                            "y" => 250,
                            "width" => 150,
                            "height" => 80
                        ]
                    ],
                    [
                        "name" => "tree",
                        "probability" => 0.88,
                        "bounding_box" => [
                            "x" => 50,
                            "y" => 10,
                            "width" => 75,
                            "height" => 200
                        ]
                    ],
                ]
            ];

            $end_time = microtime(true);
            $duration_ms = (int) (($end_time - $start_time) * 1000);

            $this->billingService->logTransaction(
                $request->user(),
                $request->workspace,
                'image_recognition',
                $duration_ms
            );

            return response()->json($aiResponse, 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                "type" => "/problem/types/400",
                "title" => "Bad Request",
                "status" => 400,
                "detail" => "The request is invalid.",
            ], 400);

        } catch (\Throwable $th) {
            return response()->json([
                "type" => "/problem/types/503",
                "title" => "Service Unavailable",
                "status" => 503,
                "detail" => "The service is currently unavailable."
            ], 503);
        }
    }
}
