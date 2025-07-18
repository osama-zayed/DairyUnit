<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptInvoiceFromStores\AddReceiptInvoiceFromStoresRequest;
use App\Http\Requests\ReceiptInvoiceFromStores\UpdateReceiptInvoiceFromStoresRequest;
use App\Http\Requests\Report\ReceiptInvoiceFromStoreRequest;
use App\Models\AssemblyStore;
use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\TransferToFactory;
use App\Models\User;
use App\Traits\FormatData;
use App\Traits\PdfTraits;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptInvoiceFromStoresController extends Controller
{
    use FormatData, PdfTraits;
    public function AddReceiptInvoiceFromCollector(AddReceiptInvoiceFromStoresRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = auth('sanctum')->user();

                $ReceiptInvoiceFromStore = ReceiptInvoiceFromStore::create([
                    'date_and_time' => $request->input('date_and_time'),
                    'quantity' => $request->input('quantity'),
                    'association_id' => $user->id,
                    'associations_branche_id' => $request->input('associations_branche_id'),
                    'notes' => $request->input('notes') ?? '',
                ]);

                $assemblyStore = AssemblyStore::updateOrCreate(
                    [
                        'association_id' => $user->id,
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $request->input('quantity')),
                    ]
                );
                self::userActivity(
                    'اضافة عملية توريد حليب ',
                    $ReceiptInvoiceFromStore,
                    ' بتوريد حليب من فرع الجمعية ' . $ReceiptInvoiceFromStore->associationsBranche->name .
                        ' الكمية ' . $ReceiptInvoiceFromStore->quantity,
                    'جمعية'
                );
                self::userNotification(
                    auth('sanctum')->user(),
                    'لقد قمت ب' .
                        ' توريد حليب من فرع الجمعية ' . $ReceiptInvoiceFromStore->associationsBranche->name .
                        ' الكمية ' . $ReceiptInvoiceFromStore->quantity
                );
                $user = User::find($request->input('associations_branche_id'));
                self::userNotification(
                    $user,
                    'لقد قامت الجمعية ب' .
                        ' توريد حليب منك' .
                        ' الكمية ' . $ReceiptInvoiceFromStore->quantity
                );
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
    }
    public function update(UpdateReceiptInvoiceFromStoresRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = auth('sanctum')->user();
                $ReceiptInvoiceFromStore = ReceiptInvoiceFromStore::where('id', $request->input("id"))
                    ->where('association_id', $user->id)
                    ->first();


                AssemblyStore::updateOrCreate(
                    [
                        'association_id' => $user->id,
                    ],
                    [
                        'quantity' => DB::raw('quantity - ' . $ReceiptInvoiceFromStore->quantity),
                    ]
                );
                $ReceiptInvoiceFromStore->update([
                    'collection_date_and_time' => $request->input('date_and_time'),
                    'associations_branche_id' => $request->input('associations_branche_id'),
                    'quantity' => $request->input('quantity'),
                    'family_id' => $request->input('family_id'),
                    'nots' => $request->input('nots') ?? '',
                ]);
                AssemblyStore::updateOrCreate(
                    [
                        'association_id' => $user->id,
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $ReceiptInvoiceFromStore->quantity),
                    ]
                );
                self::userActivity(
                    'تعديل عملية توريد الحليب ',
                    $ReceiptInvoiceFromStore,
                    ' تعديل عملية توريد الحليب من المجمع ' . $ReceiptInvoiceFromStore->associationsBranche->name .
                        ' رقم العلمية ' . $ReceiptInvoiceFromStore->id .
                        ' الكمية ' . $ReceiptInvoiceFromStore->quantity,
                    'جمعية'
                );

                self::userNotification(
                    $user,
                    'لقد قمت بتعديل ' .
                        ' توريد حليب من المجمع ' . $ReceiptInvoiceFromStore->associationsBranche->name .
                        ' الكمية ' . $ReceiptInvoiceFromStore->quantity,
                );
                $user = User::find($request->input('associations_branche_id'));
                self::userNotification(
                    $user,
                    'لقد قامت الجمعية ب' .
                        ' تعديل توريد حليب منك' .
                        ' الكمية ' . $ReceiptInvoiceFromStore->quantity
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

        return self::formatReceiptInvoiceFromStoreData($query);
    }

    public static function formatReceiptInvoiceFromStoreDataForDisplay($ReceiptInvoiceFromStore)
    {
        return array_map(function ($ReceiptInvoiceFromStore) {
            return [
                'id' => $ReceiptInvoiceFromStore->id,
                'date_and_time' => $ReceiptInvoiceFromStore->date_and_time,
                'quantity' => $ReceiptInvoiceFromStore->quantity,
                'associations_branche_name' => $ReceiptInvoiceFromStore->associationsBranche->name,
            ];
        }, $ReceiptInvoiceFromStore);
    }
    public static function report(ReceiptInvoiceFromStoreRequest $request)
    {
        $fromDate = $request["start_date_and_time"];
        $toDate = $request["end_date_and_time"];
        $query = ReceiptInvoiceFromStore::whereBetween('date_and_time', [$fromDate,  $toDate])
            ->where('association_id',  auth('sanctum')->user()->id);
        if ($request->has('associations_branche_id')) {
            $query->where('associations_branche_id', $request["associations_branche_id"]);
        }
        $ReceiptInvoiceFromStore = $query->get();
        $quantity = $ReceiptInvoiceFromStore->sum('quantity');
        $data = $ReceiptInvoiceFromStore->map(function ($query) {
            return self::formatReceiptInvoiceFromStoreData($query);
        });
        $html = view('report.association.ReceiptInvoiceFromStore', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printApiPdf($html);
    }
}
