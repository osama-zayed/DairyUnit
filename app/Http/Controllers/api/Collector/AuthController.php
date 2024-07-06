<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends UserController
{
    // public function me()
    // {
    //     // التحقق من المستخدم
    //     $user = auth('api')->user();
    //     $warehouseSummary = WarehouseSupplyOperation::where('user_id', $user->id)
    //         ->selectRaw('COUNT(DISTINCT station_id) as total_stations, SUM(quantity) as total_quantity')
    //         ->first();

    //     // احصل على مجموع quantity_expensed من exchange_issuuance_operations
    //     $exchangeSummary = ExchangeIssuanceOperation::where('user_id', $user->id)
    //         ->selectRaw('SUM(quantity_expensed) as total_quantity_expensed')
    //         ->first();

    //     // تحقق من وجود البيانات
    //     if ($warehouseSummary && $exchangeSummary) {
    //         return response()->json([
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'username' => $user->username,
    //             'phone_number' => $user->phone_number,
    //             'total_quantity' => $warehouseSummary->total_quantity ?? 0,
    //             'Quiantity spent' => $exchangeSummary->total_quantity_expensed ?? 0,
    //             'The remaining quiantity' => $warehouseSummary->total_quantity - $exchangeSummary->total_quantity_expensed,
    //             'total_stations' => $warehouseSummary->total_stations,
    //         ]);
    //     } else {
    //         return response()->json(['Status' => false, 'Message' => 'البيانات غير موجوده'], 404);
    //     }
    // }

    
}
