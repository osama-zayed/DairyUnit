<?php

namespace App\Http\Requests\TransferToFactory;

use App\Models\ReceiptInvoiceFromStore;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTransferToFactoryRequest extends FormRequest
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
            'id' => [
                'required',
                'integer',
                'exists:receipt_invoice_from_stores,id',
                function ($attribute, $value, $fail) {
                    $collectingMilkFromFamily = ReceiptInvoiceFromStore::findOrFail($value);
                    if ($collectingMilkFromFamily->association_id !== auth('sanctum')->user()->id) {
                        $fail('لم تقم انت باضافة هذه العملية');
                    }
                },
            ],
            'date_and_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $requestDateTime = \Carbon\Carbon::parse($value);
                    $now = \Carbon\Carbon::now();
                    $twoDaysAgo = \Carbon\Carbon::now()->subDays(2);

                    if ($requestDateTime->greaterThan($now)) {
                        $fail('يجب أن لا يكون تاريخ ووقت التحويل في المستقبل (بعد الوقت الحالي).');
                    }

                    if ($requestDateTime->lt($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت التحويل قبل يومين.');
                    }
                },
            ],
            'quantity' => 'required|numeric|min:1',
            // 'associations_branche_id' => 'required|exists:users,id',
            'associations_branche_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail) {
                $associationsBrancheId = User::findOrFail($value);
                if ($associationsBrancheId->association_id != auth('sanctum')->user()->id) {
                    $fail('لم تقم أنت بإضافة هذا المجمع');
                }
            }],
            'nots' => 'nullable',
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
            'id.required' => 'معرف  العملية مطلوب',
            'id.exists' => ' العملية المحددة غير موجودة',
            'date_and_time.required' => 'تاريخ ووقت الجمع مطلوب',
            'date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت الجمع صالحًا',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.numeric' => 'الكمية يجب أن تكون رقمية',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'associations_branche_id.required' => 'معرف فرع الشركة مطلوب',
            'associations_branche_id.exists' => 'فرع الشركة المحددة غير موجودة',
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
