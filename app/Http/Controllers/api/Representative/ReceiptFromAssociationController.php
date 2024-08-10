<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReceiptFromAssociationController\StoreRequest;
use App\Http\Requests\ReceiptFromAssociationController\UpdateRequest;
use App\Models\AssemblyStore;
use App\Models\ReceiptFromAssociation;
use App\Models\TransferToFactory;
use App\Models\User;
use App\Traits\FormatData;
use App\Traits\PdfTraits;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReceiptFromAssociationController extends Controller
{
    use FormatData, PdfTraits;
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
        try {
            DB::transaction(function () use ($request) {
                $transferToFactoryId = $request->input('transfer_to_factory_id');
                $quantity = $request->input('quantity');
                $transferToFactory = TransferToFactory::findOrFail($transferToFactoryId);

                $receiptFromAssociation = ReceiptFromAssociation::create([
                    'transfer_to_factory_id' => $transferToFactoryId,
                    'association_id' => $transferToFactory->association_id,
                    'driver_id' => $transferToFactory->driver_id,
                    'factory_id' => $transferToFactory->factory_id,
                    'start_time_of_collection' => $request->input('start_time_of_collection'),
                    'end_time_of_collection' => $request->input('end_time_of_collection'),
                    'quantity' => $quantity,
                    'package_cleanliness' => $request->input('package_cleanliness'),
                    'transport_cleanliness' => $request->input('transport_cleanliness'),
                    'driver_personal_hygiene' => $request->input('driver_personal_hygiene'),
                    'ac_operation' => $request->input('ac_operation'),
                    'user_id' => auth('sanctum')->user()->id,
                    'notes' => $request->input('notes') ?? '',
                ]);

                $this->updateAssemblyStoreQuantity($transferToFactory->association_id, $transferToFactory->quantity, $quantity);
                $this->updateTransferToFactoryStatus($transferToFactoryId, 1);

                $this->logUserActivity(
                    'استلام عملية تحويل حليب',
                    $receiptFromAssociation,
                    ' باستلام عملية تحويل حليب من الجمعية ' . $transferToFactory->association->name .
                        ' إلى المصنع ' . $transferToFactory->factory->name .
                        ' الكمية ' . $quantity
                );

                $this->sendNotifications($transferToFactory, $quantity);
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            // Log the error and return an appropriate response
            Log::error('Error in store method: ' . $e->getMessage());
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
    }

    private function updateAssemblyStoreQuantity(int $associationId, int $transferredQuantity, int $receivedQuantity)
    {
        AssemblyStore::where('association_id', $associationId)
            ->update([
                'quantity' => DB::raw('quantity + ' . ($transferredQuantity - $receivedQuantity)),
            ]);
    }

    private function updateTransferToFactoryStatus(int $transferToFactoryId, int $status)
    {
        TransferToFactory::where('id', $transferToFactoryId)
            ->update(['status' => $status]);
    }

    private function logUserActivity(string $action, Model $model, string $description)
    {
        self::userActivity(
            $action,
            $model,
            $description,
            'المندوب'
        );
    }

    private function sendNotifications(TransferToFactory $transferToFactory, int $quantity)
    {
        $association = User::findOrFail($transferToFactory->association_id);

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت ب' .
                'استلام عملية تحويل حليب من الجمعية ' . $transferToFactory->association->name .
                ' إلى المصنع ' . $transferToFactory->factory->name .
                ' الكمية ' . $quantity
        );

        self::userNotification(
            $association,
            'لقد تم ' .
                'استلام عملية تحويل الحليب برقم ' . $transferToFactory->id .
                ' من قبل المندوب ' . auth('sanctum')->user()->name .
                ' في مصنع ' . $transferToFactory->factory->name .
                ' الكمية المحولة ' . $transferToFactory->quantity .
                ' الكمية المستلمة ' . $quantity .
                ' الكمية الغير مصادق عليها ' . $transferToFactory->quantity - $quantity
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = auth('sanctum')->user();
            $receiptFromAssociation = ReceiptFromAssociation::where('id', $id)
                ->where('user_id',  $user->id)
                ->first();
            return self::responseSuccess(self::formatReceiptFromAssociationData($receiptFromAssociation));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, $id)
    {
        try {

            DB::transaction(function () use ($request, $id) {
                // تحقق من الكمية في الاستلام مقارنة مع الكمية في التحويل
                $receiptFromAssociation = ReceiptFromAssociation::find($request->input('id'));
                $transferToFactoryId = $receiptFromAssociation->transfer_to_factory_id;
                $quantity = $request->input('quantity');
                $transferToFactory = TransferToFactory::find($transferToFactoryId);

                // تحديث كمية المخزن في الجمعية
                AssemblyStore::where('association_id', $transferToFactory->association_id)
                    ->update([
                        'quantity' => DB::raw('quantity - ' . ($transferToFactory->quantity - $receiptFromAssociation->quantity)),
                    ]);

                // تحديث البيانات
                $receiptFromAssociation->update([
                    'transfer_to_factory_id' => $transferToFactoryId,
                    'association_id' => $transferToFactory->association_id,
                    'driver_id' => $transferToFactory->driver_id,
                    'factory_id' => $transferToFactory->factory_id,
                    'start_time_of_collection' => $request->input('start_time_of_collection'),
                    'end_time_of_collection' => $request->input('end_time_of_collection'),
                    'quantity' => $quantity,
                    'package_cleanliness' => $request->input('package_cleanliness'),
                    'transport_cleanliness' => $request->input('transport_cleanliness'),
                    'driver_personal_hygiene' => $request->input('driver_personal_hygiene'),
                    'ac_operation' => $request->input('ac_operation'),
                    'user_id' => auth('sanctum')->user()->id,
                    'notes' => $request->input('notes') ?? '',
                ]);

                // تحديث كمية المخزن في المصنع
                AssemblyStore::where('association_id', $transferToFactory->association_id)
                    ->update([
                        'quantity' => DB::raw('quantity + ' . ($transferToFactory->quantity - $quantity)),
                    ]);

                self::userActivity(
                    'تعديل عملية استلام حليب ',
                    $receiptFromAssociation,
                    ' بتعديل عملية استلام حليب من الجمعية ' . $transferToFactory->association->name .
                        'الى المصنع ' . $transferToFactory->factory->name .
                        ' الكمية ' . $quantity,
                    'المندوب'
                );

                self::userNotification(
                    auth('sanctum')->user(),
                    'لقد قمت ب' .
                        'تعديل عملية استلام حليب من الجمعية ' . $transferToFactory->association->name .
                        'الى المصنع ' . $transferToFactory->factory->name .
                        ' الكمية ' . $quantity
                );

                $association = User::find($transferToFactory->id);
                self::userNotification(
                    $association,
                    'لقد تم ' .
                        'تعديل عملية استلام الحليب برقم ' . $transferToFactoryId .
                        ' من قبل المندوب ' . auth('sanctum')->user()->name .
                        ' في مصنع ' . $transferToFactory->factory->name .
                        ' الكمية المحولة ' . $transferToFactory->quantity .
                        ' الكمية المستلمة ' . $quantity .
                        ' الكمية الغير مصادق عليها ' . ($transferToFactory->quantity - $quantity)
                );
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            // Log the error and return an appropriate response
            Log::error('Error in store method: ' . $e->getMessage());
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
    }


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
            ->with('association', 'transferToFactory')
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
                'transfer_quantity' => $ReceiptFromAssociation->transferToFactory->quantity,
                'receipt_quantity' => $ReceiptFromAssociation->quantity,
            ];
        }, $ReceiptFromAssociation);
    }

}
