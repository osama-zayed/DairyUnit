<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptInvoiceFromStores\AddReceiptInvoiceFromStoresRequest;
use App\Http\Requests\ReceiptInvoiceFromStores\UpdateReceiptInvoiceFromStoresRequest;
use App\Models\AssemblyStore;
use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptInvoiceFromStore;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptInvoiceFromStoresController extends Controller
{
    public function AddReceiptInvoiceFromCollector(AddReceiptInvoiceFromStoresRequest $request)
    {
        $warehouseSummary = CollectingMilkFromFamily::where('user_id', $request->input('associations_branche_id'))
            ->selectRaw('SUM(quantity) as total_quantity')
            ->first();

        $totalDeliveredQuantity = ReceiptInvoiceFromStore::where('associations_branche_id', $request->input('associations_branche_id'))
            ->selectRaw('SUM(quantity) as total_delivered_quantity')
            ->first()->total_delivered_quantity;

        $availableQuantity = $warehouseSummary->total_quantity - $totalDeliveredQuantity;

        // Check if the user has enough quantity available
        if ($availableQuantity < $request->input('quantity')) {
            return $this->responseError('لا يوجد لدى المجمع الكمية المطلوبة');
        }

        $ReceiptInvoiceFromStore = ReceiptInvoiceFromStore::create([
            'date_and_time' => $request->input('date_and_time'),
            'quantity' => $request->input('quantity'),
            'association_id' => auth('sanctum')->user()->id,
            'associations_branche_id' => $request->input('associations_branche_id'),
            'notes' => $request->input('notes') ?? '',
        ]);

        $assemblyStore = AssemblyStore::updateOrCreate(
            [
                'association_id' => auth('sanctum')->user()->id,
            ],
            [
                'quantity' => DB::raw('quantity + ' . $request->input('quantity')),
            ]
        );

        return $this->responseSuccess([],'تمت العملية بنجاح');
    }
    public function update(UpdateReceiptInvoiceFromStoresRequest $request)
    {

        $ReceiptInvoiceFromStore = ReceiptInvoiceFromStore::findOrFail($request->input("id"));

        // Check if the user is trying to update the record after 2 hours of creation
        $createdAt = $ReceiptInvoiceFromStore->created_at;
        $now = now();
        $diffInHours = $now->diffInHours($createdAt);
        if ($diffInHours >= 2) {
            return self::responseError('لا يمكن تعديل السجل بعد مرور ساعتين من إضافته');
        }

        $ReceiptInvoiceFromStore->update([
            'collection_date_and_time' => $request->input('date_and_time'),
            'quantity' => $request->input('quantity'),
            'family_id' => $request->input('family_id'),
            'nots' => $request->input('nots') ?? '',
        ]);

        self::userActivity(
            'تعديل عملية تجميع حليب',
            $ReceiptInvoiceFromStore,
            ' جمعية ' . $ReceiptInvoiceFromStore->association->name .
                'تجميع حليب من الاسره ' . $ReceiptInvoiceFromStore->family->name .
                ' الكمية ' . $ReceiptInvoiceFromStore->quantity,
            'فرع الجمعية'
        );

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت بتعديل' .
                'تجميع حليب من الاسره ' . $ReceiptInvoiceFromStore->family->name .
                ' الكمية ' . $ReceiptInvoiceFromStore->quantity,
        );

        return self::responseSuccess('تم التعديل بنجاح');
    }

    public static function showAll(Request $request)
    {
        try {
            return self::responseSuccess(self::getReceiptInvoiceFromStorePaginated($request));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }
    public static function showById($id)
    {
        try {
            return self::responseSuccess(self::getReceiptInvoiceFromStoreById($id));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
    public static function getReceiptInvoiceFromStorePaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');

        $user = auth('sanctum')->user();

        $query = ReceiptInvoiceFromStore::select(
            'id',
            'quantity',
            'date_and_time',
            'associations_branche_id',
        )
            ->orderByDesc('id')
            ->where('association_id',  $user->id);


        $ReceiptInvoiceFromStore = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($ReceiptInvoiceFromStore, self::formatReceiptInvoiceFromStoreDataForDisplay($ReceiptInvoiceFromStore->items()));
    }
    public static function getReceiptInvoiceFromStoreById($id)
    {
        $user = auth('sanctum')->user();

        $query = ReceiptInvoiceFromStore::select(
            'id',
            'association_id',
            'associations_branche_id',
            'quantity',
            'date_and_time',
            'notes',

        )
            ->where('association_id',  $user->id)
            ->where('id', $id)
            ->first();

        return self::formatCollectingData($query);
    }
    private static function formatCollectingData($ReceiptInvoiceFromStore)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $ReceiptInvoiceFromStore->date_and_time);
        $formattedDate = $dateTime->format('d/m/Y');
        $formattedTime = $dateTime->format('h:i A');
        $dayPeriod = self::getDayPeriodArabic($dateTime->format('A'));
        $dayOfWeek = self::getDayOfWeekArabic($dateTime->format('l'));
        return [
            'id' => $ReceiptInvoiceFromStore->id,
            'date_and_time' => $ReceiptInvoiceFromStore->date_and_time,
            'date' => $formattedDate,
            'time' => $formattedTime,
            'period' => $dayPeriod,
            'day' => $dayOfWeek,
            'quantity' => $ReceiptInvoiceFromStore->quantity,
            'association_id' => $ReceiptInvoiceFromStore->association->id,
            'association_name' => $ReceiptInvoiceFromStore->association->name,
            'association_branch_id' => $ReceiptInvoiceFromStore->associationsBranche->id,
            'association_branch_name' => $ReceiptInvoiceFromStore->associationsBranche->name,
            'nots' => $ReceiptInvoiceFromStore->nots,
        ];
    }

    public static function formatReceiptInvoiceFromStoreDataForDisplay($ReceiptInvoiceFromStore)
    {
        return array_map(function ($ReceiptInvoiceFromStore) {
            return [
                'id' => $ReceiptInvoiceFromStore->id,
                'date_and_time' => $ReceiptInvoiceFromStore->date_and_time,
                'quantity' => $ReceiptInvoiceFromStore->quantity,
                // 'associations_branche_name' => $ReceiptInvoiceFromStore->associationsBranche->name,
            ];
        }, $ReceiptInvoiceFromStore);
    }
}
