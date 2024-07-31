<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\AddCollectorRequest;
use App\Http\Requests\Collector\UpdateCollectorRequest;
use App\Http\Requests\StatusRequest;
use App\Models\User;

class CollectorController extends Controller
{

    public function showByAssociation()
    {
        $Collector  = User::select(
            'id',
            'name',
            'status',
        )
        ->orderByDesc('id')
            ->where('user_type', 'collector')
            ->where('association_id', auth('sanctum')->user()->id)
            ->get();
        return self::responseSuccess($Collector);
    }

    public function showById($id)
    {
        $Collector  = User::select(
            'id',
            'name',
            'phone',
            'status',
        )
            ->where("id", $id)
            ->where('user_type', 'collector')
            ->where('association_id', auth('sanctum')->user()->id)
            ->first();
        return self::responseSuccess($Collector);
    }

    public function add(AddCollectorRequest $request)
    {
        $Collector = User::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->id,
            'user_type' => 'collector',
            'password' => bcrypt($request->input('password')),
        ]);

        self::userActivity(
            'اضافة مجمع جديد',
            $Collector,
            ' باضافة مجمع جديد ' . $Collector->name .
                ' جمعية ' . $Collector->association->name,
                'جمعية'
        );

        self::userNotification(
            auth('sanctum')->user(),
            'لقد قمت باضافة مجمع جديد باسم ' . $Collector->name
        );

        return self::responseSuccess([], 'تمت العملية بنجاح');
    }
    public function update(UpdateCollectorRequest $request)
    {
        $Collector = User::where('id', $request->input('id'))
            ->where('association_id', auth('sanctum')->user()->id)
            ->where('user_type', 'collector')
            ->first();
    
        if (empty($Collector)) {
            return $this->responseError('المجمع غير موجود');
        }
    
        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
        ];
    
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->input('password'));
        } else {
            $data['password'] = $Collector->password;
        }
    
        $Collector->update($data);
    
        $this->userActivity(
            'تعديل مجمع ',
            $Collector,
            ' بتعديل بيانات مجمع ' . $Collector->name . ' جمعية ' . $Collector->association->name,
             'جمعية'
        );
    
        $this->userNotification(
            auth('sanctum')->user(),
            ' لقد قمت بتعديل بيانات مجمع باسم ' . $Collector->name
        );
    
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
    public function updateStatus(StatusRequest $request)
    {
        $Collector = User::where('id', $request->input('id'))
            ->where('association_id', auth('sanctum')->user()->id)
            ->where('user_type', 'collector')
            ->first();
            
        if (empty($Collector)) {
            return $this->responseError('المجمع غير موجود');
        }

        $Collector->update([
            'status' => $request->input('status'),
        ]);

        $this->userActivity(
            'تعديل حالة مجمع',
            $Collector,
            ' بتعديل حالة المجمع ' . $Collector->name . ' جمعية ' . $Collector->association->name,
             'جمعية'
        );

        $this->userNotification(
            auth('sanctum')->user(),
            ' لقد قمت بتعديل حالة المجمع باسم ' . $Collector->name
        );
        
        return $this->responseSuccess([], 'تمت العملية بنجاح');
    }
}
