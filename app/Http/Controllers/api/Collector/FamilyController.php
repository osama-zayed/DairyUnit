<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Family\AddFamilyRequest;
use App\Http\Requests\Family\UpdateFamilyRequest;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{

    public function showByAssociationBranche()
    {
        $Family  = Family::select(
            'id',
            'name',
        )
            ->where('status', 1)
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->get();
        return self::responseSuccess($Family);
    }

    public function add(AddFamilyRequest $request)
    {
        $family = Family::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->association_id,
            'associations_branche_id' => auth('sanctum')->user()->id,
        ]);
        self::userActivity(
            'اضافة اسره جديدة',
            $family,
            'اضافة اسرة جديدة ' . $family->name .
                ' جمعية ' . $family->association->name,
            'فرع الجمعية' . auth('sanctum')->user()->name
        );
        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت باضافة اسرة جديدة باسم ' . $family->name
        );
        return self::responseSuccess([], 'تمت العملية بنجاح');
    }
    public function update(UpdateFamilyRequest $request)
    {
        $family = Family::where('id',$request->input('id'))
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->first();
    
        $family->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->association_id,
            'associations_branche_id' => auth('sanctum')->user()->id,
        ]);
    
        $this->userActivity(
            'تعديل اسرة',
            $family,
            'تم تعديل بيانات اسرة ' . $family->name . ' جمعية ' . $family->association->name,
            'فرع الجمعية ' . auth('sanctum')->user()->name
        );
    
        $this->userNotification(
            auth('sanctum')->user(),
            'لقد قمت بتعديل بيانات اسرة باسم ' . $family->name
        );
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
}
