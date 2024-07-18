<?php

namespace App\Http\Controllers\Api\association;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserController;
use App\Models\AssemblyStore;
use App\Models\ReceiptInvoiceFromStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $TransferToFactory = 0;
        $numberOfCollectors = User::where('association_id', $user->id)->count();

        return self::responseSuccess([
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone,
            'total_quantity' => $totalQuantity->total_quantity ?? 0,
            'ruantity_disbursed' => $TransferToFactory ?? 0,
            'residual_quantity' => $residualQuantity->total_quantity ?? 0,
            'number_of_compilers' => $numberOfCollectors,
        ]);
    }
}
