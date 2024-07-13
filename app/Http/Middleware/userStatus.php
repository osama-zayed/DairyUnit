<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class userStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth("sanctum")->check()) {
            $userstatu = auth('sanctum')->user()->status;
            if (isset($userstatu)) {
                if ($userstatu)
                    return $next($request);
            }
            $request->user()->currentAccessToken()->delete();

            return response()->json(['Status' => false, 'Message' => 'حسابك موقف'], 403);
        } else {
            $userstatu = auth()->user()->status;
            if (isset($userstatu)) {
                if ($userstatu)
                    return $next($request);
            }
            auth()->logout();
            return redirect()->back();
        }
    }
}
