<?php

namespace App\Http\Controllers\Api\Association;

use App\Http\Controllers\Controller;
use App\Models\Factory;

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
