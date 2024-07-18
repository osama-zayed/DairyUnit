<?php

namespace App\Http\Requests\ReceiptInvoiceFromStores;

use App\Models\ReceiptInvoiceFromStore;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateReceiptInvoiceFromStoresRequest extends FormRequest
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
                        $fail('يجب أن لا يكون تاريخ ووقت الجمع في المستقبل (بعد الوقت الحالي).');
                    }

                    if ($requestDateTime->lt($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت الجمع قبل يومين.');
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
