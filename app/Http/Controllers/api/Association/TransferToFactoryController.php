<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\TransferToFactoryRequest;
use App\Http\Requests\TransferToFactory\AddTransferToFactoryRequest;
use App\Http\Requests\TransferToFactory\UpdateTransferToFactoryRequest;
use App\Models\AssemblyStore;
use App\Models\TransferToFactory;
use App\Models\User;
use App\Traits\FormatData;
use App\Traits\PdfTraits;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferToFactoryController extends Controller
{
    use FormatData, PdfTraits;
    public  function index(Request $request)
    {
        try {
            return self::responseSuccess(self::getTransferToFactoryPaginated($request));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    public  function show($id)
    {
        try {
            return self::responseSuccess(self::getTransferToFactoryById($id));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
    public function store(AddTransferToFactoryRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = auth('sanctum')->user();

                $AssemblyStore = AssemblyStore::where('association_id', $user->id)->first();


                $TransferToFactory = TransferToFactory::create([
                    'date_and_time' => $request->input('date_and_time'),
                    'quantity' => $request->input('quantity'),
                    'association_id' => $user->id,
                    'driver_id' => $request->input('driver_id'),
                    'factory_id' => $request->input('factory_id'),
                    'means_of_transportation' => $request->input('means_of_transportation'),
                    'notes' => $request->input('notes') ?? '',
                ]);

                $AssemblyStore::updateOrCreate(
                    [
                        'association_id' => $user->id,
                    ],
                    [
                        'quantity' => DB::raw('quantity - ' . $request->input('quantity')),
                    ]
                );

                self::userActivity(
                    'اضافة عملية تحويل حليب ',
                    $TransferToFactory,
                    ' بتحويل حليب من الجمعية ' . $user->name .
                        'الى المصنع ' . $TransferToFactory->factory->name .
                        ' الكمية ' . $TransferToFactory->quantity,
                    'جمعية'
                );

                self::userNotification(
                    auth('sanctum')->user(),
                    'لقد قمت ب' .
                        'تحويل حليب الى المصنع ' . $TransferToFactory->factory->name .
                        ' الكمية ' . $TransferToFactory->quantity
                );
                $users = User::where('factory_id', $TransferToFactory->factory_id)->get();
                foreach ($users as $key => $value) {
                    self::userNotification(
                        $value,
                        'لقد قامت الجمعية ' . $user->name .
                            ' بتحويل حليب ' .
                            ' الكمية ' . $TransferToFactory->quantity
                    );
                }
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
    }
    public function update(UpdateTransferToFactoryRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $user = auth('sanctum')->user();
                $TransferToFactory = TransferToFactory::where('id', $request->input('id'))->first();
                $AssemblyStore = AssemblyStore::where('association_id', $user->id)->first();

                $AssemblyStore::updateOrCreate(
                    [
                        'association_id' => $user->id,
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $TransferToFactory->quantity),
                    ]
                );
                $TransferToFactory->update([
                    'date_and_time' => $request->input('date_and_time'),
                    'quantity' => $request->input('quantity'),
                    'association_id' => $user->id,
                    'driver_id' => $request->input('driver_id'),
                    'factory_id' => $request->input('factory_id'),
                    'means_of_transportation' => $request->input('means_of_transportation'),
                    'notes' => $request->input('notes') ?? '',

                ]);
                $AssemblyStore->updateOrCreate(
                    [
                        'association_id' => $user->id,
                    ],
                    [
                        'quantity' => DB::raw('quantity - ' . $TransferToFactory->quantity),
                    ]
                );

                self::userActivity(
                    'تعديل عملية تحويل حليب ',
                    $TransferToFactory,
                    ' بتعديل عملية تحويل حليب من الجمعية ' . $user->name .
                        ' الى المصنع ' . $TransferToFactory->factory->name .
                        ' رقم المعلية ' . $TransferToFactory->id .
                        ' الكمية ' . $TransferToFactory->quantity,
                    'جمعية'
                );

                self::userNotification(
                    auth('sanctum')->user(),
                    'لقد قمت ب' .
                        'تعديل بيانات عملية تحويل حليب الى المصنع ' . $TransferToFactory->factory->name .
                        ' الكمية ' . $TransferToFactory->quantity
                );
                $users = User::where('factory_id', $TransferToFactory->factory_id)->get();
                foreach ($users as $key => $value) {
                    self::userNotification(
                        $value,
                        'لقد قامت الجمعية ' . $user->name .
                            ' بتعديل بيانات عملية تحويل حليب ' .
                            ' الكمية ' . $TransferToFactory->quantity
                    );
                }
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return self::responseError($e);
        }
    }


    public  function getTransferToFactoryPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');

        $user = auth('sanctum')->user();

        $query = TransferToFactory::select(
            'id',
            'quantity',
            'date_and_time',
            'factory_id',
            'status',
        )
            ->orderByDesc('id')
            ->where('association_id',  $user->id);


        $TransferToFactory = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($TransferToFactory, self::formatTransferToFactoryDataForDisplay($TransferToFactory->items()));
    }
    public  function getTransferToFactoryById($id)
    {
        $user = auth('sanctum')->user();

        $query = TransferToFactory::select(
            'id',
            'association_id',
            'driver_id',
            'factory_id',
            'means_of_transportation',
            'quantity',
            'date_and_time',
            'status',
            'notes',
        )
            ->where('association_id',  $user->id)
            ->where('id', $id)
            ->first();

        return self::formatTransferToFactoryData($query);
    }

    public static function report(TransferToFactoryRequest $request)
    {
        $fromDate = $request["start_date_and_time"];
        $toDate = $request["end_date_and_time"];
        $query = TransferToFactory::whereBetween('date_and_time', [$fromDate,  $toDate])
            ->where('association_id',  auth('sanctum')->user()->id);
        if ($request->has('factory_id')) {
            $query->where('factory_id', $request["factory_id"]);
        }
        if ($request->has('driver_id')) {
            $query->where('driver_id', $request["driver_id"]);
        }
        $TransferToFactory = $query->get();
        $quantity = $TransferToFactory->sum('quantity');
        $data = $TransferToFactory->map(function ($query) {
            return self::formatTransferToFactoryData($query);
        });
        $html = view('report.association.TransferToFactory', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printApiPdf($html);
    }
    public static function formatTransferToFactoryDataForDisplay($TransferToFactory)
    {
        return array_map(function ($TransferToFactory) {
            return [
                'id' => $TransferToFactory->id,
                'date_and_time' => $TransferToFactory->date_and_time,
                'quantity' => $TransferToFactory->quantity,
                'factory_name' => $TransferToFactory->factory->name,
                'status' => $TransferToFactory->status,
            ];
        }, $TransferToFactory);
    }
}
