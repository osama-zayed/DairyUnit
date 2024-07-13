<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if ($request->user()->user_type == $role) {
            return $next($request);
        } else {
            // $request->user()->currentAccessToken()->delete();
            if (auth('sanctum')->check()) {
                return response()->json([
                    'status' => 'false',
                    'message' => 'غير مصرح لك',
                ], 403);
            }
            $request->user()->logout();
            return abort(403);
        }
    }
}
