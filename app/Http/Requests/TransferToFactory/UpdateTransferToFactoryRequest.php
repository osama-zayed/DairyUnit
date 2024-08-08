<?php

namespace App\Http\Requests\TransferToFactory;

use App\Models\AssemblyStore;
use App\Models\Driver;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\TransferToFactory;
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
                'exists:transfer_to_factories,id',
                function ($attribute, $value, $fail) {
                    $collectingMilkFromFamily = TransferToFactory::findOrFail($value);
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
            'factory_id' => ['required', 'exists:factories,id'],
            'means_of_transportation' => 'required|string',
            'driver_id' => ['required', 'exists:drivers,id', function ($attribute, $value, $fail) {
                $driver = Driver::findOrFail($value);
                if ($driver->association_id !== auth('sanctum')->user()->id) {
                    $fail('لم تقم أنت بإضافة هذه السائق.');
                }
            }],
            'notes' => 'nullable|string',
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
            'date_and_time.required' => 'تاريخ ووقت التحويل مطلوب',
            'date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت التحويل بتنسيق صالح (Y-m-d H:i:s)',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.numeric' => 'الكمية يجب أن تكون رقمية',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'factory_id.required' => 'معرف المصنع مطلوب',
            'factory_id.exists' => 'المصنع المحدد غير موجود',
            'means_of_transportation.required' => 'وسيلة النقل مطلوبة',
            'means_of_transportation.string' => 'وسيلة النقل يجب أن تكون نص',
            'driver_id.required' => 'معرف السائق مطلوب',
            'driver_id.exists' => 'السائق المحدد غير موجود',
            'notes.string' => 'الملاحظات يجب أن تكون نص',
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
