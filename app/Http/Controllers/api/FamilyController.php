<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFamilyRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function showByAssociation()
    {
        $Family  = Family::select(
            'id',
            'name',
        )
            ->where('status', 1)
            ->get();
        return self::responseSuccess($Family);
    }


}
