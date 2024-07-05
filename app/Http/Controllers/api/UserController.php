<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{

    public function login(LoginRequest $request)
    {
        // Validate the login request
        $credentials = $request->validated();

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $token = null;

            // Check if a token name is provided in the request
            if ($request->has('token_name') && !is_null($request->token_name)) {
                $token = $request->user()->createToken($request->token_name);
            } else {
                $token = $request->user()->createToken('auth_token');
            }

            return response()->json([
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ]);
        }

        // If authentication fails, return a 401 Unauthorized response
        return response()->json(['error' => 'رقم الهاتف او كلمة السر غير صحيح'], 401);
    }
    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();

        // Return a success response
        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }
    public function editUser(EditUserRequest $request)
    {
        // Use validated data from EditUserRequest
        $data = $request->validated();

        // Get the authenticated user
        $user = auth('sanctum')->user();

        // Update the user's information
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        // Return a success response
        return response()->json([
            'message' => 'تم تعديل البيانات بنجاح',
            'user' => $user
        ]);
    }
    public function me()
    {
        $data = auth('sanctum')->user();
        return self::responseSuccess($data);
    }
}
