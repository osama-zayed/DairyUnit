<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddFarmerRequest;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function showByAssociation()
    {
        $farmer  = Farmer::select(
            'id',
            'name',
        )
            ->where('status', 1)
            ->get();
        return self::responseSuccess($farmer);
    }


}
