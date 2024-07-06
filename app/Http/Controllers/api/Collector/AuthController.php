<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Api\UserController;
use App\Models\CollectingMilkFromFamily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends UserController
{
    public function me()
    {
        $user = auth('sanctum')->user();
        $warehouseSummary = CollectingMilkFromFamily::where('user_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();
        $warehouseSummaryDay = CollectingMilkFromFamily::where('user_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity_day')
            ->whereDate('collection_date_and_time', \Carbon\Carbon::today()->toDateString())
            ->first();
        $exchangeSummary = 0;
        // $exchangeSummary = MilkTransfer::where('controller_id', $user->id)
        //     ->selectRaw('SUM(quantity) as total_quantity_expensed')
        //     ->first();

        $totalQuantity = $warehouseSummary->total_quantity - $exchangeSummary;

        return self::responseSuccess([
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone,
            'the_remaining_quiantity' => $warehouseSummary->total_quantity ?? 0,
            'quiantity_spent' => $exchangeSummary ?? 0,
            'total_quantity' => $totalQuantity,
            'the_remaining_quiantity_day' => $warehouseSummaryDay->total_quantity_day ?? 0,
        ]);
    }
}
