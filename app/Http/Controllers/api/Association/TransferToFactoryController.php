<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransferToFactory\AddTransferToFactoryRequest;
use App\Http\Requests\TransferToFactory\UpdateTransferToFactoryRequest;
use App\Models\AssemblyStore;
use App\Models\TransferToFactory;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferToFactoryController extends Controller
{
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
        $user = auth('sanctum')->user();
        $AssemblyStore = AssemblyStore::where('association_id', $user->id)->first();

        if ($AssemblyStore->quantity  < $request->input('quantity')) {
            return $this->responseError('لا يوجد لديك الكمية المطلوبة');
        }

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
            ' تم ' .
                'تحويل حليب من الجمعية ' . $user->name .
                'الى المصنع ' . $TransferToFactory->factory->name .
                ' الكمية ' . $TransferToFactory->quantity,
            'الجمعية'
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
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
    public function update(UpdateTransferToFactoryRequest $request)
    {
        $user = auth('sanctum')->user();
        $TransferToFactory = TransferToFactory::where('id', $request->input('id'))->first();


        if ($TransferToFactory->status) {
            return self::responseError('لا يمكن التعديل لانه تم الاستلام');
        }

        $createdAt = $TransferToFactory->created_at;
        $now = now();
        $diffInHours = $now->diffInHours($createdAt);
        if ($diffInHours >= 2) {
            return self::responseError('لا يمكن تعديل السجل بعد مرور ساعتين من إضافته');
        }

        $AssemblyStore = AssemblyStore::where('association_id', $user->id)->first();
        if ($AssemblyStore->quantity + $TransferToFactory->quantity  < $request->input('quantity')) {
            return $this->responseError('لا يوجد لديك الكمية المطلوبة');
        }
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
            ' تم ' .
                'تعديل عملية تحويل حليب من الجمعية ' . $user->name .
                'الى المصنع ' . $TransferToFactory->factory->name .
                ' الكمية ' . $TransferToFactory->quantity,
            'الجمعية'
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
        return self::responseSuccess([], 'تم التعديل بنجاح');
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

        return self::formatCollectingData($query);
    }
    public static function formatCollectingData($TransferToFactory)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $TransferToFactory->date_and_time);
        $formattedDate = $dateTime->format('d/m/Y');
        $formattedTime = $dateTime->format('h:i A');
        $dayPeriod = self::getDayPeriodArabic($dateTime->format('A'));
        $dayOfWeek = self::getDayOfWeekArabic($dateTime->format('l'));
        return [
            'id' => $TransferToFactory->id,
            'date_and_time' => $TransferToFactory->date_and_time,
            'date' => $formattedDate,
            'time' => $formattedTime,
            'period' => $dayPeriod,
            'day' => $dayOfWeek,
            'quantity' => $TransferToFactory->quantity,
            'association_id' => $TransferToFactory->association->id,
            'association_name' => $TransferToFactory->association->name,
            'driver_id' => $TransferToFactory->driver_id,
            'driver_name' => $TransferToFactory->driver->name,
            'factory_id' => $TransferToFactory->factory_id,
            'factory_name' => $TransferToFactory->factory->name,
            'means_of_transportation' => $TransferToFactory->means_of_transportation,
            'notes' => $TransferToFactory->notes,
            'status' => $TransferToFactory->status,


        ];
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
