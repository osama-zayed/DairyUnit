<?php

namespace App\Http\Controllers\Api\Association;

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
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = auth('sanctum')->user();
            $ReturnTheQuantity = ReturnTheQuantity::where('id', $id)
                ->where('association_id',  $user->id)
                ->first();
            return self::responseSuccess(self::formatDataById($ReturnTheQuantity));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }



    public function getReturnTheQuantityPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');
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
            ->with('association')
            ->where('association_id', $user->id)
            ->orderByDesc('id');

        $ReturnTheQuantity = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($ReturnTheQuantity, self::formatReturnTheQuantityDataForDisplay($ReturnTheQuantity->items()));
    }
    public static function formatReturnTheQuantityDataForDisplay($ReturnTheQuantity)
    {
        return array_map(function ($ReturnTheQuantity) {
            return [
                'id' => $ReturnTheQuantity->id,
                'quantity' =>  $ReturnTheQuantity->quantity ,
                'return_to' => ($ReturnTheQuantity->return_to == "association") ? 'مردود الى جمعية ' . $ReturnTheQuantity->association->name :
                    'مردود الى المؤسسة'
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
            'quantity' =>  $ReturnTheQuantity->quantity ,
            'return' => ($ReturnTheQuantity->return_to == "association") ? 'مردود الى جمعية ' . $ReturnTheQuantity->association->name :
                'مردود الى المؤسسة',
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
