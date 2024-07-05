<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
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

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'phone' => 'required|regex:/^[0-9]{9}$/',
            'password' => 'required|min:6|max:20',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'رقم الهاتف يجب أن يكون 9 ارقام',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
            'password.max' => 'كلمة المرور لا يمكن أن تتجاوز 20 حرفًا',
        ];
    }
}
