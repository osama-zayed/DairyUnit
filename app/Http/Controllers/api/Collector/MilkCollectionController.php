<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\CollectingRequest;
use App\Http\Requests\Collector\UpdateCollectingRequest;
use App\Http\Requests\Report\ReportMilkCollectionRequest;
use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptInvoiceFromStore;
use App\Traits\FormatData;
use App\Traits\PdfTraits;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MilkCollectionController extends Controller
{
    use FormatData, PdfTraits;
    public function collecting(CollectingRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
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
                    ' بتجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                        ' جمعية ' . $collectingMilkFromFamily->association->name .
                        ' الكمية ' . $collectingMilkFromFamily->quantity,
                    'فرع الجمعية'
                );
                self::userNotification(
                    auth('sanctum')->user(),
                    'لقد قمت ب ' .
                        ' تجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                        ' الكمية ' . $collectingMilkFromFamily->quantity,
                );
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
    }
    public function update(UpdateCollectingRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $collectingMilkFromFamily = CollectingMilkFromFamily::findOrFail($request->input("id"));

                $collectingMilkFromFamily->update([
                    'collection_date_and_time' => $request->input('date_and_time'),
                    'quantity' => $request->input('quantity'),
                    'family_id' => $request->input('family_id'),
                    'nots' => $request->input('nots') ?? '',
                ]);

                self::userActivity(
                    'تعديل عملية تجميع حليب ',
                    $collectingMilkFromFamily,
                    ' بتعديل بيانات عملية تجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                        ' جمعية ' . $collectingMilkFromFamily->association->name .
                        ' رقم العملية ' . $collectingMilkFromFamily->id .
                        ' الكمية ' . $collectingMilkFromFamily->quantity,
                    'فرع الجمعية'
                );

                self::userNotification(
                    auth('sanctum')->user(),
                    ' لقد قمت بتعديل ' .
                        ' تجميع حليب من الاسره ' . $collectingMilkFromFamily->family->name .
                        ' الكمية ' . $collectingMilkFromFamily->quantity,
                );
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
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

        return self::formatCollectingMilkFromFamilyData($query);
    }

    public static function report(ReportMilkCollectionRequest $request)
    {
        $fromDate = $request["start_date_and_time"];
        $toDate = $request["end_date_and_time"];
        $query = CollectingMilkFromFamily::whereBetween('collection_date_and_time', [$fromDate,  $toDate]);
        if ($request->has('family_id')) {
            $query->where('family_id', $request["family_id"]);
        }
        $CollectingMilkFromFamily = $query->get();
        $data = $CollectingMilkFromFamily->map(function ($query) {
            return self::formatCollectingMilkFromFamilyData($query);
        });
        $html = view('report.collector.CollectingMilkFromFamily', [
            'data' => $data,
        ])->render();
        return  self::printApiPdf($html);
    }
}
