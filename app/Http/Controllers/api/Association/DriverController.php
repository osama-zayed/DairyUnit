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
            'status',
        )
            ->where('association_id', auth('sanctum')->user()->id)
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
            ->where('association_id', auth('sanctum')->user()->id)
            ->first();
        return self::responseSuccess($Driver);
    }

    public function add(AddDriverRequest $request)
    {
        $Driver = Driver::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->id,
        ]);
        
        self::userActivity(
            'اضافة سائق جديد',
            $Driver,
            'اضافة سائق جديد ' . $Driver->name .
                ' جمعية ' . $Driver->association->name,
            'فرع الجمعية' . auth('sanctum')->user()->name
        );

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت باضافة سائق جديد باسم ' . $Driver->name
        );
        
        return self::responseSuccess([], 'تمت العملية بنجاح');
    }
    public function update(UpdateDriverRequest $request)
    {
        $Driver = Driver::where('id', $request->input('id'))
            ->where('association_id', auth('sanctum')->user()->id)
            ->first();

        $Driver->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->id,
        ]);

        $this->userActivity(
            'تعديل سائق',
            $Driver,
            'تم تعديل بيانات سائق ' . $Driver->name . ' جمعية ' . $Driver->association->name,
        );

        $this->userNotification(
            auth('sanctum')->user(),
            'لقد قمت بتعديل بيانات سائق باسم ' . $Driver->name
        );
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
}
