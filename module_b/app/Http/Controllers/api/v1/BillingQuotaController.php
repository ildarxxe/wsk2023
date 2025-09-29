<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\BillingQuota;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillingQuotaController extends Controller
{
    public function GetQuotaStatus(Request $request, $id): JsonResponse
    {
        $quota = BillingQuota::query()->where("workspace_id", $id)->first();

        $currentMonthlyCost = rand(500, 2500) / 100;

        $today = now();
        $endOfMonth = $today->copy()->endOfMonth();
        $daysRemaining = $today->diffInDays($endOfMonth);

        $response = [
            "status" => true,
            "currentMonthlyCost" => $currentMonthlyCost,
            "daysRemaining" => $daysRemaining,
        ];

        if ($quota) {
            $response["limit"] = $quota->limit;
            $response["is_exceeded"] = $currentMonthlyCost > $quota->limit;
        } else {
            $response["limit"] = null;
        }

        return response()->json($response);
    }

    public function SetQuota(Request $request, $id): JsonResponse {
        $data = $request->validate([
            "limit" => "required|numeric|min:0.01",
        ]);

        try {
            BillingQuota::query()->updateOrCreate(
                ["workspace_id" => $id],
                ["limit" => $data["limit"]]
            );
            return response()->json(["status" => true]);
        } catch (\Throwable $th) {
            return response()->json(["status" => false, "message" => $th->getMessage()]);
        }
    }

    public function DeleteQuota(Request $request, $id): JsonResponse {
        try {
            BillingQuota::query()->where("workspace_id", $id)->delete();
            return response()->json(["status" => true]);
        } catch (\Throwable $th) {
            return response()->json(["status" => false, "message" => $th->getMessage()]);
        }
    }
}
