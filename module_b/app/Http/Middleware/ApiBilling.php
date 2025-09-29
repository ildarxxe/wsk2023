<?php

namespace App\Http\Middleware;

use App\Models\BillingQuota;
use App\Models\Token;
use App\Models\UsageTransaction;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApiBilling
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $serviceName): Response
    {
        $request->attributes->set("startTime", microtime(true));

        $response = $next($request);

        $endTime = microtime(true);

        $apiToken = $request->attributes->get('apiToken');
        if ($apiToken instanceof Token) {
            $startTime = $request->get("startTime");
            $duration = round($endTime - $startTime, 4);

            $costPerSecond = $this->getServiceCost($serviceName);

            if (!$this->isQuotaExceeded($apiToken->workspace_id, $costPerSecond * $duration)) {
                UsageTransaction::query()->create([
                    'api_token_id'     => $apiToken->id,
                    'service_name'     => $serviceName,
                    'duration_seconds' => $duration,
                    'cost_per_second'  => $costPerSecond,
                ]);
            }
        }
    }

    private function getServiceCost(string $serviceName): float
    {
        return match ($serviceName) {
            'Analyze_Text' => 0.00015,
            'Image_Render' => 0.00050,
            default => 0.00010,
        };
    }

    private function isQuotaExceeded(int $ws_id, float $transaction_cost): bool {
        $quota = BillingQuota::query()->where('workspace_id', $ws_id)->first();
        if (!$quota) {
            return false;
        }

        $startOfMonth = Carbon::now()->startOfMonth();

        $currentSpending = UsageTransaction::query()->whereHas('token.workspace', function($query) use ($ws_id) {
            $query->where('id', $ws_id);
        })->where('created_at', '>=', $startOfMonth)->sum(DB::raw("duration * cost_per_second"));

        return ($currentSpending + $transaction_cost) > $quota->limit;
    }
}
