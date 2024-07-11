<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EditUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => 'required|string|min:8|max:255|current_password',
            'new_password' => 'required|string|min:8|confirmed|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessages = [];
        foreach ($validator->errors()->all() as $error) {
            $errorMessages[] = $error;
        }
        $mergedMessage = implode(" و ", $errorMessages);

        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $mergedMessage,
        ], 422));
    }

    public function messages()
    {
        return [
            "old_password.required" => "ادخل الرمز القديم",
            "old_password.min" => "الحد الأدنى للرمز القديم 8 خانات",
            "old_password.max" => "الحد الأقصى للرمز 255 خانة",
            "old_password.current_password" => "الرمز القديم غير صحيح",
            "new_password.required" => "ادخل الرمز الجديد",
            "new_password.min" => "الحد الأدنى للرمز الجديد 8 خانات",
            "new_password.max" => "الحد الأقصى للرمز 255 خانة",
            "new_password.confirmed" => "الرمز الجديد غير متطابق",
        ];
    }
}