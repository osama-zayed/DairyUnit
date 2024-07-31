<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnTheQuantityController\StoreRequest;
use App\Http\Requests\ReturnTheQuantityController\UpdateRequest;
use App\Models\AssemblyStore;
use App\Models\ReceiptFromAssociation;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\ReturnTheQuantity;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnTheQuantityController extends Controller
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

        $ReturnTheQuantity = new ReturnTheQuantity();

        $ReturnTheQuantity->user_id = auth('sanctum')->user()->id;
        $ReturnTheQuantity->factory_id = auth('sanctum')->user()->factory_id;
        $ReturnTheQuantity->return_to = $request->input('return_to');
        $ReturnTheQuantity->defective_quantity_due_to_coagulation = $request->input('defective_quantity_due_to_coagulation');
        $ReturnTheQuantity->defective_quantity_due_to_impurities = $request->input('defective_quantity_due_to_impurities');
        $ReturnTheQuantity->defective_quantity_due_to_density = $request->input('defective_quantity_due_to_density');
        $ReturnTheQuantity->defective_quantity_due_to_acidity = $request->input('defective_quantity_due_to_acidity');

        $quantity = $ReturnTheQuantity->defective_quantity_due_to_acidity +
            $ReturnTheQuantity->defective_quantity_due_to_density +
            $ReturnTheQuantity->defective_quantity_due_to_impurities +
            $ReturnTheQuantity->defective_quantity_due_to_coagulation;

        $ReturnTheQuantity->notes = $request->input('notes') ?? '';
        $userMessage = ' والمردودة الى ';
        if ($ReturnTheQuantity->return_to == 'association') {
            $ReturnTheQuantity->association_id = $request->input('association_id');
            $association = User::find($ReturnTheQuantity->association_id);
            self::userNotification(
                $association,
                'تم إنشاء مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                    ' من قبل المندوب ' . auth('sanctum')->user()->name .
                    ' في مصنع ' . auth('sanctum')->user()->factory->name .
                    ' الكمية المردودة ' . $quantity
            );
            $userMessage = $userMessage . 'جمعية '.  $association ->name ;
        } else {
            $admin_users = User::where('user_type', "admin")->get();
            foreach ($admin_users as $admin) {
                self::userNotification(
                    $admin,
                    'تم إنشاء مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                        ' من قبل المندوب ' . auth('sanctum')->user()->name .
                        ' في مصنع ' . auth('sanctum')->user()->factory->name .
                        ' الكمية المردودة ' . $quantity
                );
            }
            $userMessage = $userMessage . 'المؤاسسة' ;
        }

        $ReturnTheQuantity->save();

        self::userActivity(
            'مرتجع حليب ',
            $ReturnTheQuantity,
            ' بانشاء مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                ' في مصنع ' . auth('sanctum')->user()->factory->name .
                $userMessage .
                ' الكمية المردودة ' . $quantity,
            'المندوب'
        );

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت ب ' .
                'إنشاء مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                 $userMessage .
                ' الكمية المردودة ' . $quantity,
        );

        return self::responseSuccess([], 'تمت العملية بنجاح');
    }
    public function update(UpdateRequest $request, $id)
    {
        $ReturnTheQuantity = ReturnTheQuantity::findOrFail($request->input('id'));
    
        $ReturnTheQuantity->return_to = $request->input('return_to');
        $ReturnTheQuantity->defective_quantity_due_to_coagulation = $request->input('defective_quantity_due_to_coagulation');
        $ReturnTheQuantity->defective_quantity_due_to_impurities = $request->input('defective_quantity_due_to_impurities');
        $ReturnTheQuantity->defective_quantity_due_to_density = $request->input('defective_quantity_due_to_density');
        $ReturnTheQuantity->defective_quantity_due_to_acidity = $request->input('defective_quantity_due_to_acidity');
    
        $quantity = $ReturnTheQuantity->defective_quantity_due_to_acidity +
            $ReturnTheQuantity->defective_quantity_due_to_density +
            $ReturnTheQuantity->defective_quantity_due_to_impurities +
            $ReturnTheQuantity->defective_quantity_due_to_coagulation;
    
        $ReturnTheQuantity->notes = $request->input('notes') ?? '';
    
        if ($ReturnTheQuantity->return_to == 'association') {
            $ReturnTheQuantity->association_id = $request->input('association_id');
            $association = User::find($ReturnTheQuantity->association_id);
            self::userNotification(
                $association,
                'تم تعديل مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                    ' من قبل المندوب ' . auth('sanctum')->user()->name .
                    ' في مصنع ' . auth('sanctum')->user()->factory->name .
                    ' الكمية المردودة ' . $quantity
            );
            $userMessage = ' والمردودة الى جمعية '. $association->name;
        } else {
            $ReturnTheQuantity->association_id = null;
            $admin_users = User::where('user_type', "admin")->get();
            foreach ($admin_users as $admin) {
                self::userNotification(
                    $admin,
                    'تم تعديل مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                        ' من قبل المندوب ' . auth('sanctum')->user()->name .
                        ' في مصنع ' . auth('sanctum')->user()->factory->name .
                        ' الكمية المردودة ' . $quantity
                );
            }
            $userMessage = ' والمردودة الى المؤاسسة';
        }
    
        $ReturnTheQuantity->save();
    
        self::userActivity(
            'تعديل مرتجع حليب',
            $ReturnTheQuantity,
            ' بتعديل مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                ' في مصنع ' . auth('sanctum')->user()->factory->name .
                $userMessage .
                ' الكمية المردودة ' . $quantity,
            'المندوب'
        );
    
        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت ب ' .
                'تعديل مرتجع حليب برقم ' . $ReturnTheQuantity->id .
                $userMessage .
                ' الكمية المردودة ' . $quantity,
        );
    
        return self::responseSuccess([], 'تمت العملية بنجاح');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // try {
        //     $user = auth('sanctum')->user();
        //     $receiptFromAssociation = ReceiptFromAssociation::where('id', $id)
        //         ->where('user_id',  $user->id)
        //         ->first();
        //     return self::responseSuccess(self::formatDataById($receiptFromAssociation));
        // } catch (\Throwable $th) {
        //     return self::responseError($th);
        // }
    }


    /**
     * Update the specified resource in storage.
     */



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
            ->with('association', 'ReturnTheQuantity')
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
                'transfer_quantity' => $ReceiptFromAssociation->ReturnTheQuantity->quantity,
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
            'transfer_quantity' => $receiptFromAssociation->ReturnTheQuantity->quantity,
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
