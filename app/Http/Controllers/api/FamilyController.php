<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFamilyRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    // protected static ?string $userType = '';

    public function showByAssociation()
    {
        $Family  = Family::select(
            'id',
            'name',
        )
            ->where('status', 1)
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->get();
        return self::responseSuccess($Family);
    }

    public function add(AddFamilyRequest $request)
    {
        $family = Family::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->association_id,
        ]);
        self::userActivity(
            'اضافة اسره جديدة',
            $family,
            'اضافة اسرة جديدة ' . $family->name .
             ' جمعية ' . $family->association->name,
            'فرع الجمعية'
        );
        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت باضافة اسرة جديدة باسم ' . $family->name
        );
        return self::responseSuccess([], 'تمت العملية بنجاح');
    }
}
