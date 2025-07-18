<?php
namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Api\UserController;
use App\Models\AssemblyStore;
use App\Models\Notification;
use App\Models\ReceiptFromAssociation;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\ReturnTheQuantity;
use App\Models\TransferToFactory;
use App\Models\User;

class AuthController extends UserController
{
    public function me()
    {
        $user = auth('sanctum')->user();
        $residualQuantity = AssemblyStore::where('association_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();

        $totalQuantity = ReceiptInvoiceFromStore::where('association_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();

        $transferToFactory = ReceiptFromAssociation::where('association_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();

        $returnData = ReturnTheQuantity::where('return_to', 'association')
            ->where('association_id',  $user->id)
            ->selectRaw('SUM(quantity) as quantity')
            ->first();

        $unreadNotificationsCount = Notification::where('notifiable_type', User::class) 
            ->where('notifiable_id', $user->id)
            ->where('read_at', null)
            ->count();

        return self::responseSuccess([
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone,
            'total_quantity' => $totalQuantity->total_quantity ?? 0,
            'quantity_disbursed' => $transferToFactory->total_quantity ?? 0,
            'residual_quantity' => $residualQuantity->total_quantity ?? 0,
            'return_quantity' => $returnData->quantity ?? 0,
            'unread_notifications_count' => $unreadNotificationsCount,
        ]);
    }
}