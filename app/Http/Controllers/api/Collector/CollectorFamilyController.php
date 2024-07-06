<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Api\FamilyController;
use App\Http\Requests\AddFamilyRequest;
use App\Models\Family;

class CollectorFamilyController extends FamilyController
{
    public function add(AddFamilyRequest $request)
    {
        Family::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->association_id,
        ]);

        return self::responseSuccess([],'تمت العملية بنجاح');
    }
}
