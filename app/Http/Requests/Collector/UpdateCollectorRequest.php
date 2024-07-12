<?php

namespace App\Http\Requests\Collector;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCollectorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Implement your authorization logic here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:families,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{9}$/|unique:users,phone,'.$this->id,
            'password' => 'required|string|min:8|confirmed|max:255',

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'id.required' => 'معرف الاسرة مطلوب',
            'id.exists' => 'الاسرة المحددة غير موجودة',
            'name.required' => 'اسم العائلة مطلوب',
            'name.string' => 'اسم العائلة يجب ان يكون نص',
            'name.max' => 'اسم العائلة يجب الا يتجاوز 255 حرف',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'رقم الهاتف يجب أن يكون 9 أرقام',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            "password.required" => "ادخل الرمز الجديد",
            "password.min" => "الحد الأدنى للرمز الجديد 8 خانات",
            "password.max" => "الحد الأقصى للرمز 255 خانة",
            "password.confirmed" => "الرمز الجديد غير متطابق",
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
}