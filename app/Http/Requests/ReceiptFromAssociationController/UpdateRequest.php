<?php

namespace App\Http\Requests\ReceiptFromAssociationController;

use App\Models\ReceiptFromAssociation;
use App\Models\TransferToFactory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'id' => [
                'required',
                'integer',
            ],
            'start_time_of_collection' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $requestDateTime = \Carbon\Carbon::parse($value);
                    $now = \Carbon\Carbon::now();
                    $twoDaysAgo = \Carbon\Carbon::now()->subDays(2);

                    if ($requestDateTime->greaterThan($now)) {
                        $fail('يجب أن لا يكون تاريخ ووقت بدء الفحص بعد الوقت الحالي.');
                    }

                    if ($requestDateTime->lt($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت بدء الفحص قبل يومين من الوقت الحالي.');
                    }
                },
            ],
            'end_time_of_collection' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $end_time = \Carbon\Carbon::parse($value);

                    $now = \Carbon\Carbon::now();
                    $twoDaysAgo = \Carbon\Carbon::now()->subDays(2);

                    if ($end_time->greaterThan($now)) {
                        $fail('يجب أن لا يكون تاريخ ووقت انتهاء الفحص بعد الوقت الحالي');
                    }

                    if ($end_time->lt($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت انتهاء الفحص قبل يومين من الوقت الحالي');
                    }
                },
            ],
            // 'transfer_to_factory_id' => [
            //     'exists:transfer_to_factories,id',
            // ],
            'quantity' => 'required|numeric|min:1',
            'package_cleanliness' => 'required|in:clean,somewhat_clean,not_clean',
            'transport_cleanliness' => 'required|in:clean,somewhat_clean,not_clean',
            'driver_personal_hygiene' => 'required|in:clean,somewhat_clean,not_clean',
            'ac_operation' => 'required|in:on,off,not_available',
            // 'defective_quantity_due_to_coagulation' => 'required|numeric|min:0',
            // 'defective_quantity_due_to_impurities' => 'required|numeric|min:0',
            // 'defective_quantity_due_to_density' => 'required|numeric|min:0',
            // 'defective_quantity_due_to_acidity' => 'required|numeric|min:0',
            'notes' => 'nullable',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth('sanctum')->user();
            $receiptFromAssociation = ReceiptFromAssociation::find($this->input('id'));
            if ($receiptFromAssociation) {
                if ($receiptFromAssociation->user_id != $user->id) {
                    $validator->errors()->add('id', 'لم تقم انت باضافة هذه العملية');
                }
                $createdAt = $receiptFromAssociation->created_at;
                $now = now();
                $diffInHours = $now->diffInHours($createdAt);
                if ($diffInHours >= 2) {
                    $validator->errors()->add('id', 'لا يمكن تعديل السجل بعد مرور ساعتين من إضافته');
                }

                $lastTransferToFactory = TransferToFactory::where('factory_id', $user->factory_id)
                    ->select('created_at')
                    ->latest()
                    ->first();

                $lastReceiptFromAssociation = ReceiptFromAssociation::where('user_id', $user->id)
                    ->select('created_at')
                    ->latest()
                    ->first();

                if ($receiptFromAssociation && (
                    ($receiptFromAssociation->created_at < $lastReceiptFromAssociation->created_at) ||
                    ($receiptFromAssociation->created_at < $lastTransferToFactory->created_at)
                )) {
                    $validator->errors()->add('id', 'لا يمكن التعديل لأنه حصل عملية في وقت لاحق');
                }

                $quantity = $this->input('quantity');
                $startTimeOfCollection = $this->input('start_time_of_collection');
                $endTimeOfCollection = $this->input('end_time_of_collection');
                
                $start = \Carbon\Carbon::parse($startTimeOfCollection);
                $end = \Carbon\Carbon::parse($endTimeOfCollection);
                
                if ($end->lessThan($start)) {
                    $validator->errors()->add('end_time_of_collection', 'يجب أن يكون تاريخ ووقت انتهاء الفحص بعد تاريخ ووقت بدء الفحص.');
                }
                
                $transferToFactoryId = $receiptFromAssociation->transfer_to_factory_id;
                // تحقق من وجود معرف التحويل
                if ($transferToFactoryId) {
                    $transferToFactory = TransferToFactory::find($transferToFactoryId);

                    if ($transferToFactory) {

                        if ($quantity > $transferToFactory->quantity) {
                            $validator->errors()->add('quantity', 'لا يمكن أن تكون الكمية في الاستلام أكبر من الكمية في التحويل');
                        }
                    }
                }
            }else{
                $validator->errors()->add('id', 'عملية الاستلام غير موجودة');
            }
        });
    }
    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'id.required' => 'معرف  العملية مطلوب',
            'id.exists' => ' العملية المحددة غير موجودة',
            'id.integer' => 'معرف العملية يجب ان يكون رقم ',
            'date_and_time.required' => 'تاريخ ووقت الجمع مطلوب',
            'date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت الجمع صالحًا',
            'transfer_to_factory_id.exists' => 'معرف عملية التحويل المحدده غير موجوده',
            'start_time_of_collection.required' => 'وقت بداية الجمع مطلوب',
            'end_time_of_collection.required' => 'وقت نهاية الجمع مطلوب',
            'quantity.required' => 'عدد العبوات مطلوب',
            'quantity.numeric' => 'عدد العبوات يجب أن يكون رقميًا',
            'quantity.min' => 'عدد العبوات يجب أن يكون على الأقل 1',
            'package_cleanliness.required' => 'نظافة العبوات مطلوبة',
            'package_cleanliness.in' => 'نظافة العبوات يجب أن تكون "clean" أو "somewhat_clean" أو "not_clean"',
            'transport_cleanliness.required' => 'نظافة النقل مطلوبة',
            'transport_cleanliness.in' => 'نظافة النقل يجب أن تكون "clean" أو "somewhat_clean" أو "not_clean"',
            'driver_personal_hygiene.required' => 'نظافة الشخصية للسائق مطلوبة',
            'driver_personal_hygiene.in' => 'نظافة الشخصية للسائق يجب أن تكون "clean" أو "somewhat_clean" أو "not_clean"',
            'ac_operation.required' => 'حالة نظام التكييف مطلوبة',
            'ac_operation.in' => 'حالة نظام التكييف يجب أن تكون "on" أو "off" أو "not_available"',
            // 'defective_quantity_due_to_coagulation.required' => 'الكمية المعيبة بسبب التخثر مطلوبة',
            // 'defective_quantity_due_to_coagulation.numeric' => 'الكمية المعيبة بسبب التخثر يجب أن تكون رقمية',
            // 'defective_quantity_due_to_coagulation.min' => 'الكمية المعيبة بسبب التخثر يجب أن تكون على الأقل 0',
            // 'defective_quantity_due_to_impurities.required' => 'الكمية المعيبة بسبب الشوائب مطلوبة',
            // 'defective_quantity_due_to_impurities.numeric' => 'الكمية المعيبة بسبب الشوائب يجب أن تكون رقمية',
            // 'defective_quantity_due_to_impurities.min' => 'الكمية المعيبة بسبب الشوائب يجب أن تكون على الأقل 0',
            // 'defective_quantity_due_to_density.required' => 'الكمية المعيبة بسبب الكثافة مطلوبة',
            // 'defective_quantity_due_to_density.numeric' => 'الكمية المعيبة بسبب الكثافة يجب أن تكون رقمية',
            // 'defective_quantity_due_to_density.min' => 'الكمية المعيبة بسبب الكثافة يجب أن تكون على الأقل 0',
            // 'defective_quantity_due_to_acidity.required' => 'الكمية المعيبة بسبب الحموضة مطلوبة',
            // 'defective_quantity_due_to_acidity.numeric' => 'الكمية المعيبة بسبب الحموضة يجب أن تكون رقمية',
            // 'defective_quantity_due_to_acidity.min' => 'الكمية المعيبة بسبب الحموضة يجب أن تكون على الأقل 0',
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
