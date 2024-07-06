<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\CollectingRequest;

use App\Models\CollectingMilkFromFamily;
use DateTime;
use Illuminate\Http\Request;


class MilkCollectionController extends Controller
{
    public function collecting(CollectingRequest $request)
    {
        CollectingMilkFromFamily::create([
            'collection_date_and_time' => $request->input('date_and_time'),
            'quantity' => $request->input('quantity'),
            'association_id' => auth('sanctum')->user()->association_id,
            'family_id' => $request->input('family_id'),
            'user_id' => auth('sanctum')->user()->id,
            'nots' => $request->input('nots') ?? '',
        ]);
        return self::responseSuccess('تمت العملية بنجاح');
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
            'date' => $formattedDate,
            'time' => $formattedTime,
            'period' => $dayPeriod,
            'day' => $dayOfWeek,
            'quantity' => $CollectingMilkFromFamily->quantity,
            'family_name' => $CollectingMilkFromFamily->Family->name,
            'association_name' => $CollectingMilkFromFamily->association->name,
            'association_branch_name' => $CollectingMilkFromFamily->user->name,
            'nots' => $CollectingMilkFromFamily->nots,
        ];
    }
    private static function getDayPeriodArabic($dayPeriod)
    {
        return $dayPeriod === 'AM' ? 'صباحًا' : 'مساءً';
    }
    private static function getDayOfWeekArabic($dayOfWeek)
    {
        $daysOfWeekArabic = [
            'Monday' => 'الاثنين',
            'Tuesday' => 'الثلاثاء',
            'Wednesday' => 'الأربعاء',
            'Thursday' => 'الخميس',
            'Friday' => 'الجمعة',
            'Saturday' => 'السبت',
            'Sunday' => 'الأحد',
        ];

        return $daysOfWeekArabic[$dayOfWeek];
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
