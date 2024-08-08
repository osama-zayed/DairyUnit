<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Models\User;

class AssociationController extends Controller
{
    public  function index()
    {
        try {
            $user = User::select('id', 'name')
                ->where('user_type', "association")
                ->get();
            return self::responseSuccess($user);
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }
}
