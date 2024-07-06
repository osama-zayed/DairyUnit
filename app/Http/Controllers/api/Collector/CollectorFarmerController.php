<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Api\FarmerController;
use App\Http\Requests\AddFarmerRequest;
use App\Models\Farmer;

class CollectorFarmerController extends FarmerController
{
    public function add(AddFarmerRequest $request)
    {
        Farmer::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'association_id' => auth('sanctum')->user()->association_id,
        ]);

        return self::responseSuccess([],'تمت العملية بنجاح');
    }
}
