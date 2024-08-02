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
        if ($request->user()->status) {
            return $next($request);
        } else {
            if ($request->is('api/*')) {
                $request->user()->currentAccessToken()->delete();
                return response()->json([
                    'status' => 'false',
                    'message' => 'حسابك موقف',
                ], 403);
            } else {
                auth()->logout();
                return abort(403);
            }
        }
    }
}
