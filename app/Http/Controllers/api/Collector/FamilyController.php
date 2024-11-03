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
        $families = Family::select(
            'id',
            'name',
            'status'
        )
            ->orderByDesc('id')
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->get();

        return self::responseSuccess($families);
    }

    public function showById($id)
    {
        $family = Family::with(['governorate', 'directorate', 'isolation', 'village']) // استخدام علاقات Eloquent
            ->select(
                'id',
                'name',
                'phone',
                'local_cows_producing',
                'local_cows_non_producing',
                'born_cows_producing',
                'born_cows_non_producing',
                'imported_cows_producing',
                'imported_cows_non_producing',
                'governorate_id',
                'directorate_id',
                'isolation_id',
                'village_id'
            )
            ->where("id", $id)
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->first();
    
        if (!$family) {
            return self::responseError('الأسرة المحددة غير موجودة');
        }
    
        // تحويل الأرقام إلى أسماء
        return self::responseSuccess([
            'id' => $family->id,
            'name' => $family->name,
            'phone' => $family->phone,
            'local_cows_producing' => $family->local_cows_producing,
            'local_cows_non_producing' => $family->local_cows_non_producing,
            'born_cows_producing' => $family->born_cows_producing,
            'born_cows_non_producing' => $family->born_cows_non_producing,
            'imported_cows_producing' => $family->imported_cows_producing,
            'imported_cows_non_producing' => $family->imported_cows_non_producing,
            'governorate' => $family->governorate->name ?? null, // استخدام العلاقة لجلب الاسم
            'directorate' => $family->directorate->name ?? null,
            'isolation' => $family->isolation->name ?? null,
            'village' => $family->village->name ?? null,
            'governorate_id'=> $family->governorate_id,
            'directorate_id'=> $family->directorate_id,
            'isolation_id'=> $family->isolation_id,
            'village_id'=> $family->village_id,
        ]);
    }

    public function add(AddFamilyRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $family = Family::create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'local_cows_producing' => $request->input('local_cows_producing'),
                    'local_cows_non_producing' => $request->input('local_cows_non_producing'),
                    'born_cows_producing' => $request->input('born_cows_producing'),
                    'born_cows_non_producing' => $request->input('born_cows_non_producing'),
                    'imported_cows_producing' => $request->input('imported_cows_producing'),
                    'imported_cows_non_producing' => $request->input('imported_cows_non_producing'),
                    'governorate_id' => $request->input('governorate_id'),
                    'directorate_id' => $request->input('directorate_id'),
                    'isolation_id' => $request->input('isolation_id'),
                    'village_id' => $request->input('village_id'),
                    'association_id' => auth('sanctum')->user()->association_id,
                    'associations_branche_id' => auth('sanctum')->user()->id,
                ]);

                self::userActivity(
                    'اضافة اسره جديدة',
                    $family,
                    'بإضافة أسرة جديدة ' . $family->name .
                    ' جمعية ' . $family->association->name,
                    'فرع الجمعية'
                );

                self::userNotification(
                    auth('sanctum')->user(),
                    'لقد قمت بإضافة أسرة جديدة باسم ' . $family->name
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

                if (!$family) {
                    return self::responseError('الأسرة المحددة غير موجودة');
                }

                $family->update([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'local_cows_producing' => $request->input('local_cows_producing'),
                    'local_cows_non_producing' => $request->input('local_cows_non_producing'),
                    'born_cows_producing' => $request->input('born_cows_producing'),
                    'born_cows_non_producing' => $request->input('born_cows_non_producing'),
                    'imported_cows_producing' => $request->input('imported_cows_producing'),
                    'imported_cows_non_producing' => $request->input('imported_cows_non_producing'),
                    'governorate_id' => $request->input('governorate_id'),
                    'directorate_id' => $request->input('directorate_id'),
                    'isolation_id' => $request->input('isolation_id'),
                    'village_id' => $request->input('village_id'),
                    'association_id' => auth('sanctum')->user()->association_id,
                    'associations_branche_id' => auth('sanctum')->user()->id,
                ]);

                $this->userActivity(
                    'تعديل اسرة',
                    $family,
                    'بتعديل بيانات أسرة ' . $family->name . ' جمعية ' . $family->association->name,
                    'فرع الجمعية'
                );

                $this->userNotification(
                    auth('sanctum')->user(),
                    'لقد قمت بتعديل بيانات أسرة باسم ' . $family->name
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
            return $this->responseError('الأسرة غير موجودة');
        }

        $family->update([
            'status' => $request->input('status'),
        ]);

        $this->userActivity(
            'تعديل حالة اسرة',
            $family,
            'بتعديل حالة الأسرة ' . $family->name . ' جمعية ' . $family->association->name,
            'فرع الجمعية'
        );

        $this->userNotification(
            auth('sanctum')->user(),
            'لقد قمت بتعديل حالة الأسرة باسم ' . $family->name
        );

        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
}