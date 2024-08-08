<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Family\AddFamilyRequest;
use App\Http\Requests\Family\UpdateFamilyRequest;
use App\Http\Requests\StatusRequest;
use App\Models\Family;
use Illuminate\Support\Facades\DB;

class FamilyController extends Controller
{

    public function showByAssociationBranche()
    {
        $Family  = Family::select(
            'id',
            'name',
            'status',
        )
            ->orderByDesc('id')
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->get();
        return self::responseSuccess($Family);
    }

    public function showById($id)
    {
        $Family  = Family::select(
            'id',
            'name',
            'phone',
            'number_of_cows_produced',
            'number_of_cows_unproductive',
        )
            ->where("id", $id)
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->first();
        return self::responseSuccess($Family);
    }

    public function add(AddFamilyRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $family = Family::create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'number_of_cows_produced' => $request->input('number_of_cows_produced'),
                    'number_of_cows_unproductive' => $request->input('number_of_cows_unproductive'),
                    'association_id' => auth('sanctum')->user()->association_id,
                    'associations_branche_id' => auth('sanctum')->user()->id,
                ]);

                self::userActivity(
                    'اضافة اسره جديدة',
                    $family,
                    ' باضافة اسرة جديدة ' . $family->name .
                        ' جمعية ' . $family->association->name,
                    ' فرع الجمعية'
                );

                self::userNotification(
                    auth('sanctum')->user(),
                    ' لقد قمت باضافة اسرة جديدة باسم ' . $family->name
                );
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
    }
    public function update(UpdateFamilyRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $family = Family::where('id', $request->input('id'))
                    ->where('associations_branche_id', auth('sanctum')->user()->id)
                    ->first();

                $family->update([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'number_of_cows_produced' => $request->input('number_of_cows_produced'),
                    'number_of_cows_unproductive' => $request->input('number_of_cows_unproductive'),
                    'association_id' => auth('sanctum')->user()->association_id,
                    'associations_branche_id' => auth('sanctum')->user()->id,
                ]);

                $this->userActivity(
                    'تعديل اسرة',
                    $family,
                    ' بتعديل بيانات اسرة ' . $family->name . ' جمعية ' . $family->association->name,
                    ' فرع الجمعية '
                );

                $this->userNotification(
                    auth('sanctum')->user(),
                    ' لقد قمت بتعديل بيانات اسرة باسم ' . $family->name
                );
            });

            return self::responseSuccess([], 'تمت العملية بنجاح');
        } catch (\Exception $e) {
            return self::responseError('حدث خطأ أثناء تنفيذ العملية');
        }
    }
    public function updateStatus(StatusRequest $request)
    {

        $family = Family::where('id', $request->input('id'))
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->first();

        if (empty($family)) {
            return $this->responseError('الاسرة غير موجود');
        }


        $family->update([
            'status' => $request->input('status'),
        ]);

        $this->userActivity(
            'تعديل حالة اسرة',
            $family,
            ' بتعديل حالة الاسرة ' . $family->name . ' جمعية ' . $family->association->name,
            'فرع الجمعية'
        );

        $this->userNotification(
            auth('sanctum')->user(),
            'لقد قمت بتعديل حالة الاسرة باسم ' . $family->name
        );

        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
}
