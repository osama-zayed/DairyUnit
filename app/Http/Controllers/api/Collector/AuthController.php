<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Api\UserController;
use App\Models\CollectingMilkFromFamily;
use App\Models\Notification;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\User;

class AuthController extends UserController
{
    public function me()
    {
        $user = auth('sanctum')->user();
        $totalQuantity = CollectingMilkFromFamily::where('user_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();
        $warehouseSummaryDay = CollectingMilkFromFamily::where('user_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity_day')
            ->whereDate('collection_date_and_time', \Carbon\Carbon::today()->toDateString())
            ->first();
        $exchangeSummary = ReceiptInvoiceFromStore::where('associations_branche_id', $user->id)
            ->selectRaw('SUM(quantity) as total_delivered_quantity')
            ->first()->total_delivered_quantity;

        $warehouseSummary = $totalQuantity->total_quantity - $exchangeSummary;

        $unreadNotificationsCount = Notification::where('notifiable_type', User::class) 
        ->where('notifiable_id', $user->id)
        ->where('read_at', null)
        ->count();
        
        return self::responseSuccess([
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone,
            'total_quantity' => $totalQuantity->total_quantity ?? 0,
            'the_remaining_quiantity' => $warehouseSummary ?? 0,
            'quiantity_spent' => $exchangeSummary ?? 0,
            'the_remaining_quiantity_day' => $warehouseSummaryDay->total_quantity_day ?? 0,
            'unread_notifications_count' => $unreadNotificationsCount,

        ]);
    }
}
