<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\CollectingRequest;
use App\Http\Requests\Collector\UpdateCollectingRequest;
use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptInvoiceFromStore;
use DateTime;
use Illuminate\Http\Request;


class MilkCollectionController extends Controller
{
    public function collecting(CollectingRequest $request)
    {

        $collectingMilkFromFamily = CollectingMilkFromFamily::create([
            'collection_date_and_time' => $request->input('date_and_time'),
            'quantity' => $request->input('quantity'),
            'association_id' => auth('sanctum')->user()->association_id,
            'family_id' => $request->input('family_id'),
            'user_id' => auth('sanctum')->user()->id,
            'nots' => $request->input('nots') ?? '',
        ]);
        self::userActivity(
            'اضافة عملية تجميع حليب ',
            $collectingMilkFromFamily,
            ' جمعية ' . $collectingMilkFromFamily->association->name .
                ' تجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                ' الكمية ' . $collectingMilkFromFamily->quantity,
            'فرع الجمعية'
        );
        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت ب ' .
                ' تجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                ' الكمية ' . $collectingMilkFromFamily->quantity,
        );
        return self::responseSuccess('تمت العملية بنجاح');
    }
    public function update(UpdateCollectingRequest $request)
    {

        $collectingMilkFromFamily = CollectingMilkFromFamily::findOrFail($request->input("id"));
        $createdAtReceiptInvoiceFromStore = ReceiptInvoiceFromStore::where('associations_branche_id', auth('sanctum')->user()->id)
            ->orderByDesc('id')
            ->first();

        // Check if the user is trying to update the record after 2 hours of creation
        $createdAt = $collectingMilkFromFamily->created_at;
        if (!is_null($createdAtReceiptInvoiceFromStore))
            if ($createdAtReceiptInvoiceFromStore->created_at >= $createdAt) {
                return self::responseError('لا يمكن تعديل السجل لانه حصل عملية في وقت لاحق');
            }
        $now = now();
        $diffInHours = $now->diffInHours($createdAt);
        if ($diffInHours >= 2) {
            return self::responseError('لا يمكن تعديل السجل بعد مرور ساعتين من إضافته');
        }


        $collectingMilkFromFamily->update([
            'collection_date_and_time' => $request->input('date_and_time'),
            'quantity' => $request->input('quantity'),
            'family_id' => $request->input('family_id'),
            'nots' => $request->input('nots') ?? '',
        ]);

        self::userActivity(
            'تعديل عملية تجميع حليب ',
            $collectingMilkFromFamily,
            ' جمعية ' . $collectingMilkFromFamily->association->name .
                ' تجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                ' الكمية ' . $collectingMilkFromFamily->quantity,
            'فرع الجمعية'
        );

        self::userNotification(
            auth('sanctum')->user(),
            ' لقد قمت بتعديل ' .
                ' تجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                ' الكمية ' . $collectingMilkFromFamily->quantity,
        );

        return self::responseSuccess([], 'تم التعديل بنجاح');
    }

    public static function showAll(Request $request)
    {
        try {
            return self::responseSuccess(self::getCollectingMilkFromFamilyPaginated($request));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }
    public static function showById($id)
    {
        try {
            return self::responseSuccess(self::getCollectingMilkFromFamilyById($id));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
    public static function getCollectingMilkFromFamilyPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');

        $user = auth('sanctum')->user();

        $query = CollectingMilkFromFamily::select(
            'id',
            'collection_date_and_time',
            'quantity',
            'family_id',
        )
            ->orderByDesc('id')
            ->where('association_id',  $user->association_id)
            ->where('user_id',  $user->id);


        $collectingMilkFromFamily = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($collectingMilkFromFamily, self::formatCollectingMilkFromFamilyDataForDisplay($collectingMilkFromFamily->items()));
    }
    public static function getCollectingMilkFromFamilyById($id)
    {
        $user = auth('sanctum')->user();

        $query = CollectingMilkFromFamily::select(
            'id',
            'collection_date_and_time',
            'nots',
            'quantity',
            'association_id',
            'family_id',
            'user_id',
        )

            ->where('association_id',  $user->association_id)
            ->where('user_id',  $user->id)
            ->where('id', $id)
            ->first();

        return self::formatCollectingData($query);
    }
    private static function formatCollectingData($CollectingMilkFromFamily)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $CollectingMilkFromFamily->collection_date_and_time);
        $formattedDate = $dateTime->format('d/m/Y');
        $formattedTime = $dateTime->format('h:i A');
        $dayPeriod = self::getDayPeriodArabic($dateTime->format('A'));
        $dayOfWeek = self::getDayOfWeekArabic($dateTime->format('l'));

        return [
            'id' => $CollectingMilkFromFamily->id,
            'collection_date_and_time' => $CollectingMilkFromFamily->collection_date_and_time,
            'date' => $formattedDate,
            'time' => $formattedTime,
            'period' => $dayPeriod,
            'day' => $dayOfWeek,
            'quantity' => $CollectingMilkFromFamily->quantity,
            'family_id' => $CollectingMilkFromFamily->family_id,
            'family_name' => $CollectingMilkFromFamily->Family->name,
            'association_name' => $CollectingMilkFromFamily->association->name,
            'association_branch_name' => $CollectingMilkFromFamily->user->name,
            'nots' => $CollectingMilkFromFamily->nots,
        ];
    }

    public static function formatCollectingMilkFromFamilyDataForDisplay($collectingMilkFromFamily)
    {
        return array_map(function ($collectingMilkFromFamily) {
            return [
                'id' => $collectingMilkFromFamily->id,
                // 'date_and_time' => $collectingMilkFromFamily->collection_date_and_time,
                'quantity' => $collectingMilkFromFamily->quantity,
                'family_name' => $collectingMilkFromFamily->Family->name,
            ];
        }, $collectingMilkFromFamily);
    }
}
