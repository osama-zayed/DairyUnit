<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptFromAssociationController\StoreRequest;
use App\Http\Requests\ReceiptFromAssociationController\UpdateRequest;
use App\Models\AssemblyStore;
use App\Models\ReceiptFromAssociation;
use App\Models\TransferToFactory;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptFromAssociationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public  function index(Request $request)
    {
        try {
            return self::responseSuccess(self::getReceiptFromAssociationPaginated($request));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $transferToFactoryId = $request->input('transfer_to_factory_id');
        $quantity = $request->input('quantity');
        $transferToFactory = TransferToFactory::find($transferToFactoryId);
        $receiptFromAssociation = ReceiptFromAssociation::create([
            'transfer_to_factory_id' => $transferToFactoryId,
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
            'user_id' => auth('sanctum')->user()->id,
            'notes' => $request->input('notes') ?? '',
        ]);
        $transferToFactory->update([
            'status' => 1
        ]);
        AssemblyStore::where('association_id', $transferToFactory->association_id)
            ->update(
                [
                    'quantity' => DB::raw('quantity + ' . $transferToFactory->quantity - $quantity),
                ]
            );

        self::userActivity(
            'استلام عملية تحويل حليب ',
            $receiptFromAssociation,
                ' باستلام عملية تحويل حليب من الجمعية ' . $transferToFactory->association->name .
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
                ' الكمية المستلمة ' . $quantity .
                ' الكمية الغير مصادق عليها ' . $transferToFactory->quantity - $quantity
        );
        return self::responseSuccess([], 'تمت العملية بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = auth('sanctum')->user();
            $receiptFromAssociation = ReceiptFromAssociation::where('id', $id)
                ->where('user_id',  $user->id)
                ->first();
            return self::responseSuccess(self::formatDataById($receiptFromAssociation));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request,  $id)
    {
        // تحقق من الكمية في الاستلام مقارنة مع الكمية في التحويل
        $receiptFromAssociation = ReceiptFromAssociation::find($request->input('id'));
        $transferToFactoryId = $receiptFromAssociation->transfer_to_factory_id;
        $quantity = $request->input('quantity');
        $transferToFactory = TransferToFactory::find($transferToFactoryId);

        AssemblyStore::where('association_id', $transferToFactory->association_id)
            ->update(
                [
                    'quantity' => DB::raw('quantity - ' . $transferToFactory->quantity - $receiptFromAssociation->quantity),
                ]
            );

        // تحديث البيانات
        $receiptFromAssociation->update([
            'transfer_to_factory_id' => $transferToFactoryId,
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
            'user_id' => auth('sanctum')->user()->id,
            'notes' => $request->input('notes') ?? '',
        ]);

        AssemblyStore::where('association_id', $transferToFactory->association_id)
            ->update(
                [
                    'quantity' => DB::raw('quantity + ' . $transferToFactory->quantity - $quantity),
                ]
            );

        self::userActivity(
            'تعديل عملية استلام حليب ',
            $receiptFromAssociation,
                ' بتعديل عملية استلام حليب من الجمعية ' . $transferToFactory->association->name .
                'الى المصنع ' . $transferToFactory->factory->name .
                ' الكمية ' . $quantity,
            'المندوب'
        );

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت ب' .
                'تعديل عملية استلام حليب من الجمعية ' . $transferToFactory->association->name .
                'الى المصنع ' . $transferToFactory->factory->name .
                ' الكمية ' . $quantity
        );

        $association = User::find($transferToFactory->association_id);
        self::userNotification(
            $association,
            'لقد تم ' .
                'تعديل عملية استلام الحليب برقم ' . $transferToFactoryId .
                ' من قبل المندوب ' . auth('sanctum')->user()->name .
                ' في مصنع ' . $transferToFactory->factory->name .
                ' الكمية المحولة ' . $transferToFactory->quantity .
                ' الكمية المستلمة ' . $quantity .
                ' الكمية الغير مصادق عليها ' . ($transferToFactory->quantity - $quantity)
        );

        return self::responseSuccess([], 'تمت العملية بنجاح');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReceiptFromAssociation $receiptFromAssociation)
    {
        //
    }

    public function getReceiptFromAssociationPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');
        $user = auth('sanctum')->user();
        $query = ReceiptFromAssociation::select(
            'id',
            'transfer_to_factory_id',
            'association_id',
            'quantity'
        )
            ->with('association', 'transferToFactory')
            ->where('user_id',  $user->id)
            ->orderByDesc('id');
        $ReceiptFromAssociation = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($ReceiptFromAssociation, self::formatReceiptFromAssociationDataForDisplay($ReceiptFromAssociation->items()));
    }
    public static function formatReceiptFromAssociationDataForDisplay($ReceiptFromAssociation)
    {
        return array_map(function ($ReceiptFromAssociation) {
            return [
                'id' => $ReceiptFromAssociation->id,
                'association_name' => $ReceiptFromAssociation->association->name,
                'transfer_quantity' => $ReceiptFromAssociation->transferToFactory->quantity,
                'receipt_quantity' => $ReceiptFromAssociation->quantity,
            ];
        }, $ReceiptFromAssociation);
    }

    public static function formatDataById($receiptFromAssociation)
    {
        $startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $receiptFromAssociation->start_time_of_collection);
        $startFormattedDate = $startDateTime->format('d/m/Y');
        $startFormattedTime = $startDateTime->format('h:i A');
        $startDayPeriod = self::getDayPeriodArabic($startDateTime->format('A'));
        $startDayOfWeek = self::getDayOfWeekArabic($startDateTime->format('l'));

        $endDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $receiptFromAssociation->end_time_of_collection);
        $endFormattedDate = $endDateTime->format('d/m/Y');
        $endFormattedTime = $endDateTime->format('h:i A');
        $endDayPeriod = self::getDayPeriodArabic($endDateTime->format('A'));
        $endDayOfWeek = self::getDayOfWeekArabic($endDateTime->format('l'));

        return [
            'id' => $receiptFromAssociation->id,
            'start_time_of_collection' => $receiptFromAssociation->start_time_of_collection,
            'end_time_of_collection' => $receiptFromAssociation->end_time_of_collection,
            'start_date' => $startFormattedDate,
            'end_date' => $endFormattedDate,
            'start_time' => $startFormattedTime,
            'end_time' => $endFormattedTime,
            'start_period' => $startDayPeriod,
            'end_period' => $endDayPeriod,
            'start_day' => $startDayOfWeek,
            'end_day' => $endDayOfWeek,
            'transfer_to_factory_id' => $receiptFromAssociation->transfer_to_factory_id,
            'transfer_quantity' => $receiptFromAssociation->transferToFactory->quantity,
            'receipt_quantity' => $receiptFromAssociation->quantity,
            'association_id' => $receiptFromAssociation->association->id,
            'association_name' => $receiptFromAssociation->association->name,
            'driver_id' => $receiptFromAssociation->driver_id,
            'driver_name' => $receiptFromAssociation->driver->name,
            'factory_id' => $receiptFromAssociation->factory_id,
            'factory_name' => $receiptFromAssociation->factory->name,
            'package_cleanliness' => $receiptFromAssociation->package_cleanliness,
            'transport_cleanliness' => $receiptFromAssociation->transport_cleanliness,
            'driver_personal_hygiene' => $receiptFromAssociation->driver_personal_hygiene,
            'ac_operation' => $receiptFromAssociation->ac_operation,
            'notes' => $receiptFromAssociation->notes,
        ];
    }
}
