<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\Isolation;
use App\Models\Village;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function governorate()
    {
        $governorates = Governorate::select('id','name')->get();
        return self::responseSuccess($governorates);
    }
    public function directorate(Request $request)
    {
        $governorateId = $request->input('governorate_id');
        $governorates = Directorate::select('id','name')
            ->where('governorate_id', $governorateId)
            ->get();
        return self::responseSuccess($governorates);
    }
    public function isolation(Request $request)
    {
        $directorateId = $request->input('directorate_id');
        $governorates = Isolation::select('id','name')
            ->where('directorate_id', $directorateId)
            ->get();
        return self::responseSuccess($governorates);
    }
    public function village(Request $request)
    {
        $villageId = $request->input('isolation_id');
        $governorates = Village::select('id','name')
            ->where('isolation_id', $villageId)
            ->get();
        return self::responseSuccess($governorates);
    }
}
