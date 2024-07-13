<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Http\Requests\Factory\AddFactoryRequest;
use App\Http\Requests\Factory\UpdateFactoryRequest;
use App\Http\Requests\StatusRequest;
use App\Models\Factory;
use Illuminate\Http\Request;

class FactoryController extends Controller
{

    public function showByAssociation()
    {
        $Factory  = Factory::select(
            'id',
            'name',
        )
            ->orderByDesc('id')
            ->where('status', 1)
            ->get();
        return self::responseSuccess($Factory);
    }
}
