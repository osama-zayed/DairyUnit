<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Models\Directorate;
use App\Models\Governorate;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function governorate()
    {
        $governorates = Governorate::select('name')->get();
        return self::responseSuccess($governorates);
    }
    public function directorate(Request $request)
    {
        $governorateId = $request->input('governorate_id');
        $governorates = Directorate::select('name')
            ->where('governorate_id', $governorateId)
            ->get();
        return self::responseSuccess($governorates);
    }
}
