<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\CollectingRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\CollectingMilkFromCaptivity;
use Illuminate\Http\Request;


class MilkCollectionController extends Controller
{
    public function collecting(CollectingRequest $request)
    {
        CollectingMilkFromCaptivity::create([
            'collection_date_and_time' => $request->input('collection_date_and_time'),
            'quantity' => $request->input('quantity'),
            'association_id' => auth('sanctum')->user()->association_id,
            'farmer_id' => $request->input('farmer_id'),
            'user_id' => auth('sanctum')->user()->id,
            'nots' => $request->input('nots'),
        ]);
        return self::responseSuccess('تمت العملية بنجاح');
    }

    public function showAll()
    {
        $collectingMilkFromCaptivity = CollectingMilkFromCaptivity::select(
            'id',
            'collection_date_and_time',
            'quantity',
            'association_id',
            'farmer_id',
            'user_id',
        )->get();
        return self::responseSuccess($collectingMilkFromCaptivity);
    }
}
