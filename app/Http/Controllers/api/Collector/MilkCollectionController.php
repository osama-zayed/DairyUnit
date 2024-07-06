<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collector\CollectingRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\CollectingMilkFromFamily;
use Illuminate\Http\Request;


class MilkCollectionController extends Controller
{
    public function collecting(CollectingRequest $request)
    {
        CollectingMilkFromFamily::create([
            'collection_date_and_time' => $request->input('collection_date_and_time'),
            'quantity' => $request->input('quantity'),
            'association_id' => auth('sanctum')->user()->association_id,
            'family_id' => $request->input('family_id'),
            'user_id' => auth('sanctum')->user()->id,
            'nots' => $request->input('nots'),
        ]);
        return self::responseSuccess('تمت العملية بنجاح');
    }

    public function showAll()
    {
        $CollectingMilkFromFamily = CollectingMilkFromFamily::select(
            'id',
            'collection_date_and_time',
            'quantity',
            'association_id',
            'family_id',
            'user_id',
        )->get();
        return self::responseSuccess($CollectingMilkFromFamily);
    }
}
