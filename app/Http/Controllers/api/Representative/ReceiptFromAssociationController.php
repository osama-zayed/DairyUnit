<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptFromAssociationController\StoreRequest;
use App\Models\ReceiptFromAssociation;
use App\Models\TransferToFactory;
use App\Models\User;
use Illuminate\Http\Request;

class ReceiptFromAssociationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return self::responseError('jjj');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $transferToFactoryId = $request->input('transfer_to_factory_id');
        $quantity = $request->input('quantity');
        $transferToFactory = TransferToFactory::find($transferToFactoryId);
        // return self::responseSuccess([$transferToFactory],'تمت العملية بنجاح');
        $receiptFromAssociation = ReceiptFromAssociation::create([
            'transfer_id' => $transferToFactoryId,
            'association_id' => $transferToFactory->association_id,
            'driver_id' => $transferToFactory->driver_id,
            'factory_id' => $transferToFactory->factory_id,
            'start_time_of_collection' => $request->input('start_time_of_collection'),
            'end_time_of_collection' => $request->input('end_time_of_collection'),
            'quantity' => $quantity,
            'package_cleanliness' => $request->input('package_cleanliness'),
            'transport_cleanliness' => $request->input('transport_cleanliness'),
            'driver_personal_hygiene' => $request->input('driver_personal_hygiene'),
            'ac_operation' => $request->input('ac_operation'),
            'notes' => $request->input('notes') ?? '',
        ]);
        return self::responseSuccess([],'تمت العملية بنجاح');

        $transferToFactory->update([
            'status' => 1
        ]);

        self::userActivity(
            'استلام عملية تحويل حليب ',
            $receiptFromAssociation,
            ' تم ' .
                'استلام عملية تحويل حليب من الجمعية ' . $transferToFactory->association->name .
                'الى المصنع ' . $transferToFactory->factory->name .
                ' الكمية ' . $quantity,
            'المندوب'
        );

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت ب' .
                'استلام عملية تحويل حليب من الجمعية ' . $transferToFactory->association->name .
                'الى المصنع ' . $transferToFactory->factory->name .
                ' الكمية ' . $quantity
        );
        $association = User::find($transferToFactory->association_id);
        self::userNotification(
            $association,
            'لقد تم ' .
                'استلام عملية تحويل الحليب برقم ' . $transferToFactoryId .
                ' من قبل المندوب ' . auth('sanctum')->user()->name .
                ' في مصنع ' . $transferToFactory->factory->name .
                ' الكمية المحولة ' . $transferToFactory->quantity .
                ' الكمية المستلمة ' . $quantity
        );
        return self::responseSuccess([],'تمت العملية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReceiptFromAssociation $receiptFromAssociation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReceiptFromAssociation $receiptFromAssociation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceiptFromAssociation $receiptFromAssociation)
    {
        //
    }
}
