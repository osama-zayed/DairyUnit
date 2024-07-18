<?php

namespace App\Http\Requests\ReceiptInvoiceFromStores;

use Illuminate\Foundation\Http\FormRequest;

class AddReceiptInvoiceFromStoresRequest extends FormRequest
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
            'date_and_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $requestDateTime = \Carbon\Carbon::parse($value);
                    $now = \Carbon\Carbon::now();
                    $twoDaysAgo = \Carbon\Carbon::now()->subDays(2);

                    if ($requestDateTime->greaterThan($now)) {
                        $fail('يجب أن لا يكون تاريخ ووقت الجمع في المستقبل (بعد الوقت الحالي).');
                    }

                    if ($requestDateTime->lt($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت الجمع قبل يومين.');
                    }
                },
            ],
            'quantity' => 'required|numeric|min:1',
            'associations_branche_id' => 'required|exists:users,id',
            // 'associations_branche_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail) {
            //     if ($value != auth('sanctum')->user()->id) {
            //         $fail('لم تقم أنت بإضافة هذا المجمع');
            //     }
            // }],
            'nots' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'date_and_time.required' => 'تاريخ ووقت الجمع مطلوب',
            'date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت الجمع صالحًا',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.numeric' => 'الكمية يجب أن تكون رقمية',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'associations_branche_id.required' => 'معرف فرع الشركة مطلوب',
            'associations_branche_id.exists' => 'فرع الشركة المحددة غير موجودة',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errorMessages = $validator->errors()->all();
        $mergedMessage = implode(' و ', $errorMessages);

        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'success' => false,
            'message' => $mergedMessage,
        ], 422));
    }
}
