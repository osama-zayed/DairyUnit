<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReturnTheQuantityController\StoreRequest;
use App\Http\Requests\ReturnTheQuantityController\UpdateRequest;
use App\Models\AssemblyStore;
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
            return self::responseSuccess(self::getReturnTheQuantityPaginated($request));
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
            $userMessage = $userMessage . 'جمعية ' .  $association->name;
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
            $userMessage = $userMessage . 'المؤاسسة';
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
            $userMessage = ' والمردودة الى جمعية ' . $association->name;
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
        try {
            $user = auth('sanctum')->user();
            $ReturnTheQuantity = ReturnTheQuantity::where('id', $id)
                ->where('user_id',  $user->id)
                ->where('factory_id',  $user->factory_id)
                ->first();
            return self::responseSuccess(self::formatDataById($ReturnTheQuantity));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }


    /**
     * Update the specified resource in storage.
     */



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnTheQuantity $ReturnTheQuantity)
    {
        //
    }

    public function getReturnTheQuantityPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');
        $returnTo = $request->get('return_to');
        $user = auth('sanctum')->user();
        $query = ReturnTheQuantity::select(
            'id',
            'association_id',
            'return_to',
            'defective_quantity_due_to_coagulation',
            'defective_quantity_due_to_impurities',
            'defective_quantity_due_to_density',
            'defective_quantity_due_to_acidity',
            'user_id',
        )
            ->where('user_id',  $user->id)
            ->where('factory_id',  $user->factory_id)
            ->with('association')
            ->orderByDesc('id');
        if ($returnTo == 'association') {
            $query->where('association_id', '!=', null);
        } else {
            $query->where('association_id',  null);
        }
        $ReturnTheQuantity = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($ReturnTheQuantity, self::formatReturnTheQuantityDataForDisplay($ReturnTheQuantity->items()));
    }
    public static function formatReturnTheQuantityDataForDisplay($ReturnTheQuantity)
    {
        return array_map(function ($ReturnTheQuantity) {
            return [
                'id' => $ReturnTheQuantity->id,
                'quantity' =>  $ReturnTheQuantity->defective_quantity_due_to_acidity +
                    $ReturnTheQuantity->defective_quantity_due_to_density +
                    $ReturnTheQuantity->defective_quantity_due_to_impurities +
                    $ReturnTheQuantity->defective_quantity_due_to_coagulation,
                'return_to' => ($ReturnTheQuantity->return_to == "association") ? 'مردود الى جمعية ' . $ReturnTheQuantity->association->name :
                    'مردود الى المؤاسسة'
            ];
        }, $ReturnTheQuantity);
    }

    public static function formatDataById($ReturnTheQuantity)
    {
        $DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $ReturnTheQuantity->created_at);
        $FormattedDate = $DateTime->format('d/m/Y');
        $FormattedTime = $DateTime->format('h:i A');
        $DayPeriod = self::getDayPeriodArabic($DateTime->format('A'));
        $DayOfWeek = self::getDayOfWeekArabic($DateTime->format('l'));

        return [
            'id' => $ReturnTheQuantity->id,
            'date' => $FormattedDate,
            'time' => $FormattedTime,
            'period' => $DayPeriod,
            'day' => $DayOfWeek,
            'quantity' =>  $ReturnTheQuantity->defective_quantity_due_to_acidity +
                $ReturnTheQuantity->defective_quantity_due_to_density +
                $ReturnTheQuantity->defective_quantity_due_to_impurities +
                $ReturnTheQuantity->defective_quantity_due_to_coagulation,
            'return' => ($ReturnTheQuantity->return_to == "association") ? 'مردود الى جمعية ' . $ReturnTheQuantity->association->name :
                'مردود الى المؤاسسة',
            'return_to' => $ReturnTheQuantity->return_to,

            'association_id' => $ReturnTheQuantity->association_id,
            'defective_quantity_due_to_coagulation' => $ReturnTheQuantity->defective_quantity_due_to_coagulation,
            'defective_quantity_due_to_impurities' => $ReturnTheQuantity->defective_quantity_due_to_impurities,
            'defective_quantity_due_to_density' => $ReturnTheQuantity->defective_quantity_due_to_density,
            'defective_quantity_due_to_acidity' => $ReturnTheQuantity->defective_quantity_due_to_acidity,

            'notes' => $ReturnTheQuantity->notes,
        ];
    }
}
