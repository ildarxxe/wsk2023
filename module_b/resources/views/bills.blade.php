@extends("layouts.main")
@section("title", "Счета")

@section("content")
    @php
        $grandTotal = 0;
        $groupedBills = $bills->groupBy('api_token_name');
    @endphp

    <div class="d-flex justify-content-center mt-5">
        <div class="bills_container p-4 border rounded shadow-sm bg-white" style="width: 600px; max-width: 95%;">

            <div class="d-flex justify-content-end mb-4">
                <select name="month" id="month" class="form-control" style="width: 150px;">
                    <option>Select Month</option>
                    <option>Month 1</option>
                    <option>Month 2</option>
                    <option>Month 3</option>
                </select>
            </div>

            <div class="row align-items-center font-weight-bold border-bottom pb-2 mb-3 mx-0" style="color: #495057;">
                <div class="col-6">
                    Token
                </div>
                <div class="col-6 d-flex justify-content-between text-right pl-0 pr-1">
                    <span style="width: 30%;">Time</span>
                    <span style="width: 35%;">Per sec.</span>
                    <span style="width: 35%;">Total</span>
                </div>
            </div>

            @foreach($groupedBills as $tokenName => $tokenBills)
                <h6 class="text-muted font-weight-bold text-uppercase mt-4 mb-2">{{ $tokenName }} token</h6>

                <div class="token-group">
                    @foreach($tokenBills as $bill)
                        @php
                            $usageDurationSec = $bill['usage_duration_in_ms'] / 1000;
                            $costPerSecond = $bill['service_cost_per_ms'] * 1000;
                            $totalCost = $bill['service_cost_per_ms'] * $bill['usage_duration_in_ms'];
                            $grandTotal += $totalCost;
                        @endphp

                        <div class="row align-items-center py-1 mx-0" style="color: #212529;">
                            <div class="col-6">
                                {{ $bill['service_name'] }}
                            </div>

                            <div class="col-6 d-flex justify-content-between text-right pl-0 pr-1">
                                <span class="text-muted" style="width: 30%;">
                                    {{ number_format($usageDurationSec, 3) }} s
                                </span>
                                <span class="text-muted" style="width: 35%;">
                                    $ {{ number_format($costPerSecond, 4) }}
                                </span>
                                <span style="width: 35%;">
                                    $ {{ number_format($totalCost, 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <hr class="mt-4 mb-2">
            <div class="row align-items-center font-weight-bold mx-0">
                <div class="col-6">
                    Total
                </div>
                <div class="col-6 d-flex justify-content-end pr-1">
                    $ {{ number_format($grandTotal, 2) }}
                </div>
            </div>

        </div>
    </div>
@endsection
