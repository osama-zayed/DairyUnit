<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class UserController extends Controller
{

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $token = null;

            if ($request->has('token_name') && !is_null($request->token_name)) {
                $token = $request->user()->createToken($request->token_name);
            } else {
                $token = $request->user()->createToken('auth_token');
            }

            return self::responseSuccess([
                'access_token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ], 'تم تسجيل الدخول بنجاح');
        }

        return self::responseError('رقم الهاتف او كلمة السر غير صحيح', 401);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return self::responseSuccess([], 'تم تسجيل الخروج بنجاح');
    }
    public function editUser(EditUserRequest $request)
    {
        $user = auth('sanctum')->user();
        $user->update([
            'password' => bcrypt($request->input('new_password')),
        ]);

        return self::responseSuccess([], 'تم تعديل البيانات بنجاح');
    }
    
    public function me()
    {
        $data = auth('sanctum')->user();
        return self::responseSuccess($data);
    }
    public function notification()
    {
        try {
            $user = Auth::user();
            $notifications = $user->notifications()->take(2000)->get()->toArray();
            $data = [];
            foreach ($notifications as $notification) {
                $notificationData = [
                    "description" => $notification['data']['data'],
                    "created_at" => date('H:i Y-m-d', strtotime($notification['created_at'])),
                ];
                $data[] = $notificationData;
            }
            return self::responseSuccess($data);
        } catch (Exception $e) {
            return self::responseError($e);
        }
    }
    
}
