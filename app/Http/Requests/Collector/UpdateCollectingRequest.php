<?php

namespace App\Http\Requests\Collector;

use App\Models\CollectingMilkFromFamily;
use App\Models\Family;
use App\Models\ReceiptInvoiceFromStore;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectingRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                'exists:collecting_milk_from_families,id'
            ],
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

                    if ($requestDateTime->lessThan($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت الجمع قبل يومين.');
                    }
                },
            ],
            'quantity' => [
                'required',
                'numeric',
                'min:1',
            ],
            'family_id' => [
                'required',
                'exists:families,id'
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // تحقق من أن المستخدم هو من أضاف هذه العائلة
            $collectingMilkFromFamily = CollectingMilkFromFamily::findOrFail($this->input("id"));
            if ($collectingMilkFromFamily->user_id !== auth('sanctum')->user()->id) {
                $validator->errors()->add('user_id', 'لم تقم أنت بإضافة هذه العملية');
            }
    
            // تحقق من أن العائلة تنتمي إلى فرع المستخدم
            $family = Family::findOrFail($this->input("family_id"))->associations_branche_id;
            if ($family !== auth('sanctum')->user()->id) {
                $validator->errors()->add('date_and_time', 'لم تقم أنت بإضافة هذه الأسرة');
            }
    
            // تحقق من أنه لم يتم إجراء أي عملية لاحقة
            $createdAtReceiptInvoiceFromStore = ReceiptInvoiceFromStore::where('associations_branche_id', auth('sanctum')->user()->id)
                ->orderByDesc('id')
                ->first();
            $createdAt = $collectingMilkFromFamily->created_at;
            if (!is_null($createdAtReceiptInvoiceFromStore) && $createdAtReceiptInvoiceFromStore->created_at >= $createdAt) {
                $validator->errors()->add('date_and_time', 'لا يمكن تعديل السجل لانه حصل عملية في وقت لاحق');
            }
    
            // تحقق من أنه لم يمر أكثر من ساعتين منذ إضافة السجل
            $now = now();
            $diffInHours = $now->diffInHours($createdAt);
            if ($diffInHours >= 2) {
                $validator->errors()->add('date_and_time', 'لا يمكن تعديل السجل بعد مرور ساعتين من إضافته');
            }
        });
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'id.required' => 'معرف عملية التجميع مطلوب',
            'id.exists' => 'عملية التجميع المحددة غير موجودة',
            'date_and_time.required' => 'تاريخ ووقت الجمع مطلوب',
            'date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت الجمع صالحًا',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.numeric' => 'الكمية يجب أن تكون رقمية',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'family_id.required' => 'معرف الاسرة مطلوب',
            'family_id.exists' => 'الاسرة المحددة غير موجودة',
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
