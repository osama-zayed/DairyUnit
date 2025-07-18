<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Api\UserController;
use App\Models\Notification;
use App\Models\ReceiptFromAssociation;
use App\Models\ReturnTheQuantity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends UserController
{

    public function me()
    {
        $user = auth('sanctum')->user();
    
        $receiptFromAssociation = ReceiptFromAssociation::where('user_id', $user->id)
            ->where('association_id', '!=', null)
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();
    
        $returnData = ReturnTheQuantity::where('user_id', $user->id)
            ->whereIn('return_to', ['association', 'institution'])
            ->selectRaw('return_to, SUM(quantity) as quantity')
            ->groupBy('return_to')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->return_to => $item->quantity];
            });
    
        $returnToAssociation = $returnData['association'] ?? 0;
        $returnToInstitution = $returnData['institution'] ?? 0;

        $unreadNotificationsCount = Notification::where('notifiable_type', User::class) 
        ->where('notifiable_id', $user->id)
        ->where('read_at', null)
        ->count();
        
        return self::responseSuccess([
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone,
            'total_quantity' => ($receiptFromAssociation->total_quantity - $returnToAssociation - $returnToInstitution) ?? 0,
            'receipt_quantity' => $receiptFromAssociation->total_quantity ?? 0,
            'return_to_association' => $returnToAssociation,
            'return_to_institution' => $returnToInstitution,
            'unread_notifications_count' => $unreadNotificationsCount,

        ]);
    }
}
