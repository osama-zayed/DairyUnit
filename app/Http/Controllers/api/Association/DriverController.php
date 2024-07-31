<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\AddDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Http\Requests\StatusRequest;
use App\Models\Driver;

class DriverController extends Controller
{

    public function showByAssociation()
    {
        $Driver  = Driver::select(
            'id',
            'name',
            'status',
        )
        ->orderByDesc('id')
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
            'اضافة سائق',
            $Driver,
            ' باضافة سائق جديد ' . $Driver->name .
                ' جمعية ' . $Driver->association->name,
                 'جمعية'
        );

        self::userNotification(
            auth('sanctum')->user(),
            ' لقد قمت باضافة سائق جديد باسم ' . $Driver->name
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
            ' بتعديل بيانات سائق ' . $Driver->name . ' جمعية ' . $Driver->association->name,
        );

        $this->userNotification(
            auth('sanctum')->user(),
            ' لقد قمت بتعديل بيانات سائق باسم ' . $Driver->name
        );
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
    public function updateStatus(StatusRequest $request)
    {
        $Driver = Driver::where('id', $request->input('id'))
            ->where('association_id', auth('sanctum')->user()->id)
            ->first();
            
        if (empty($Driver)) {
            return $this->responseError('السائق غير موجود');
        }

    
        $Driver->update([
            'status' => $request->input('status'),
        ]);

        $this->userActivity(
            'تعديل حالة سائق',
            $Driver,
            ' بتعديل حالة السائق ' . $Driver->name . ' جمعية ' . $Driver->association->name,
             'جمعية'
        );

        $this->userNotification(
            auth('sanctum')->user(),
            'لقد قمت بتعديل حالة السائق باسم ' . $Driver->name
        );
        
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
}
