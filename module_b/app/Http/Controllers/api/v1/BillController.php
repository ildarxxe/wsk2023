<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function GetBills(Request $request, $id): JsonResponse
    {
        $bills = Bill::query()->where("workspace_id", $id)->with('items.token')->orderBy("billing_month", "desc")->get();

        $formattedBills = $bills->map(function ($bill) {
            $billDetails = $bill->items->map(function ($item) {
                return [
                    'token_name' => $item->token->name,
                    'service_name' => $item->service_name,
                    'usage_seconds' => $item->usage_seconds,
                    'cost_per_second' => $item->cost_per_second,
                    'total_item_cost' => round($item->usage_seconds * $item->cost_per_second, 2),
                ];
            });

            return [
                "id" => $bill->id,
                "billing_month" => $bill->month,
                "total_cost" => round($bill->total_cost, 2),
                "details" => $billDetails->groupBy("token_name"),
            ];
        });

        return response()->json(["status" => true, "bills" => $formattedBills]);
    }

    public function GetBillDetails($billId): JsonResponse
    {
        $bill = Bill::query()
            ->with(['items.token', 'workspace'])
            ->find($billId);

        if (!$bill) {
            return response()->json(["status" => false, "message" => "Bill not found"], 404);
        }

        if ($bill->workspace->user_id !== auth()->id()) {
            return response()->json(["status" => false, "message" => "Unauthorized"], 403);
        }

        return response()->json(["status" => true, "bill" => $bill]);
    }
}
