<?php

namespace App\Http\Requests\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddDriverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{9}$/|unique:drivers,phone',
            'number_of_cows_produced' => 'required|integer',
            'number_of_cows_unproductive' => 'required|integer',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نصًا',
            'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'رقم الهاتف يجب أن يكون 9 أرقام',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'number_of_cows_produced.required' => 'عدد الابقار المنتجة مطلوب',
            'number_of_cows_produced.integer' =>  'عدد الابقار المنتجة يجب ان يكون رقم',
            'number_of_cows_unproductive.required' =>  'عدد الابقار الغير منتجة مطلوب',
            'number_of_cows_unproductive.integer' =>  'عدد الابقار الغير منتجة يجب ان يكون رقم',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
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
