<?php

namespace App\Http\Controllers\Api\association;

use App\Http\Controllers\Api\UserController;
use App\Models\AssemblyStore;
use App\Models\ReceiptInvoiceFromStore;
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

        $TransferToFactory = TransferToFactory::where('association_id', $user->id)
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();

        $numberOfCollectors = User::where('association_id', $user->id)->count();

        return self::responseSuccess([
            'id' => $user->id,
            'name' => $user->name,
            'phone_number' => $user->phone,
            'total_quantity' => $totalQuantity->total_quantity ?? 0,
            'ruantity_disbursed' => $TransferToFactory->total_quantity ?? 0,
            'residual_quantity' => $residualQuantity->total_quantity ?? 0,
            'number_of_compilers' => $numberOfCollectors,
        ]);
    }
}
