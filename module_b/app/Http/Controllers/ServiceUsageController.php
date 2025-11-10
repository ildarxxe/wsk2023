<?php

namespace App\Http\Controllers;

use App\Models\ServiceUsage;
use Illuminate\Http\Request;

class ServiceUsageController extends Controller
{
    public function viewBills(Request $request) {
        $bills = ServiceUsage::query()->where("username", $request->user()->name)->get();
        return view("bills")->with("bills", $bills);
    }
}
