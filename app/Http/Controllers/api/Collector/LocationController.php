<?php

namespace App\Http\Controllers\Api\Collector;

use App\Http\Controllers\Controller;
use App\Models\Directorate;
use App\Models\Governorate;
use App\Models\Isolation;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function governorate()
    {
        $governorates = Governorate::select('id', 'name')->get();
        return self::responseSuccess($governorates);
    }

    public function directorate(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'governorate_id' => 'required|exists:governorates,id', // تحقق من وجود governorate_id
        ], [
            'governorate_id.required' => 'حقل معرف المحافظة مطلوب.',
            'governorate_id.exists' => 'المحافظة المحددة غير موجودة.',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }

        $governorateId = $request->input('governorate_id');
        $directorates = Directorate::select('id', 'name')
            ->where('governorate_id', $governorateId)
            ->get();
        return self::responseSuccess($directorates);
    }

    public function isolation(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'directorate_id' => 'required|exists:directorates,id', // تحقق من وجود directorate_id
        ], [
            'directorate_id.required' => 'حقل معرف المديرية مطلوب.',
            'directorate_id.exists' => 'المديرية المحددة غير موجودة.',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }

        $directorateId = $request->input('directorate_id');
        $isolations = Isolation::select('id', 'name')
            ->where('directorate_id', $directorateId)
            ->get();
        return self::responseSuccess($isolations);
    }

    public function village(Request $request)
    {
        // التحقق من صحة البيانات
        $validator = Validator::make($request->all(), [
            'isolation_id' => 'required|exists:isolations,id', // تحقق من وجود isolation_id
        ], [
            'isolation_id.required' => 'حقل معرف العزلة مطلوب.',
            'isolation_id.exists' => 'العزلة المحددة غير موجودة.',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors());
        }

        $isolationId = $request->input('isolation_id');
        $villages = Village::select('id', 'name')
            ->where('isolation_id', $isolationId)
            ->get();
        return self::responseSuccess($villages);
    }
}