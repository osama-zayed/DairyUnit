<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\AddDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{

    public function showByAssociation()
    {
        $Driver  = Driver::select(
            'id',
            'name',
        )
            ->where('status', 1)
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->get();
        return self::responseSuccess($Driver);
    }

    public function showById($id)
    {
        $Driver  = Driver::select(
            'id',
            'name',
            'phone',
            'status',
        )
            ->where("id", $id)
            ->where('association_id', auth('sanctum')->user()->association_id)
            ->first();
        return self::responseSuccess($Driver);
    }

    public function add(AddDriverRequest $request)
    {
        $Driver = Driver::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->association_id,
        ]);
        
        self::userActivity(
            'اضافة اسره جديدة',
            $Driver,
            'اضافة اسرة جديدة ' . $Driver->name .
                ' جمعية ' . $Driver->association->name,
            'فرع الجمعية' . auth('sanctum')->user()->name
        );

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت باضافة اسرة جديدة باسم ' . $Driver->name
        );
        
        return self::responseSuccess([], 'تمت العملية بنجاح');
    }
    public function update(UpdateDriverRequest $request)
    {
        $Driver = Driver::where('id', $request->input('id'))
            ->where('associations_branche_id', auth('sanctum')->user()->id)
            ->first();

        $Driver->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'number_of_cows_produced' => $request->input('number_of_cows_produced'),
            'number_of_cows_unproductive' => $request->input('number_of_cows_unproductive'),
            'association_id' => auth('sanctum')->user()->association_id,
            'associations_branche_id' => auth('sanctum')->user()->id,
        ]);

        $this->userActivity(
            'تعديل اسرة',
            $Driver,
            'تم تعديل بيانات اسرة ' . $Driver->name . ' جمعية ' . $Driver->association->name,
            'فرع الجمعية ' . auth('sanctum')->user()->name
        );

        $this->userNotification(
            auth('sanctum')->user(),
            'لقد قمت بتعديل بيانات اسرة باسم ' . $Driver->name
        );
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
}
