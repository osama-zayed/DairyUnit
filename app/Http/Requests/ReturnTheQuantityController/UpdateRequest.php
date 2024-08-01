<?php

namespace App\Http\Requests\ReturnTheQuantityController;

use App\Models\ReceiptFromAssociation;
use App\Models\ReturnTheQuantity;
use App\Models\TransferToFactory;
use App\Models\User;
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
            'association_id' => 'nullable|exists:users,id',
            'return_to' => 'required|in:institution,association',
            'defective_quantity_due_to_coagulation' => 'required|numeric|min:0',
            'defective_quantity_due_to_impurities' => 'required|numeric|min:0',
            'defective_quantity_due_to_density' => 'required|numeric|min:0',
            'defective_quantity_due_to_acidity' => 'required|numeric|min:0',
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
            $ReturnTheQuantity = ReturnTheQuantity::find($this->input('id'));
            if ($ReturnTheQuantity) {
                if ($ReturnTheQuantity->user_id != $user->id) {
                    $validator->errors()->add('id', 'لم تقم انت باضافة هذه العملية');
                }
                $createdAt = $ReturnTheQuantity->created_at;
                $now = now();
                $diffInHours = $now->diffInHours($createdAt);
                if ($diffInHours >= 2) {
                    $validator->errors()->add('id', 'لا يمكن تعديل السجل بعد مرور ساعتين من إضافته');
                }

                $lastReceiptFromAssociation = ReceiptFromAssociation::where('factory_id', $user->factory_id)
                    ->where('user_id', $user->id)
                    ->select('created_at')
                    ->latest()
                    ->first();

                $lastReturnTheQuantity = ReturnTheQuantity::where('user_id', $user->id)
                    ->select('created_at')
                    ->latest()
                    ->first();

                if ($ReturnTheQuantity && (
                    ($ReturnTheQuantity->created_at < $lastReturnTheQuantity->created_at) ||
                    ($ReturnTheQuantity->created_at < $lastReceiptFromAssociation->created_at)
                )) {
                    $validator->errors()->add('id', 'لا يمكن التعديل لأنه حصل عملية في وقت لاحق');
                }

                $returnTo = $this->input('return_to');
                $receiptFromAssociationId = $this->input('association_id');
                /// التحقق من الكميات
                $receiptFromAssociation = ReceiptFromAssociation::where('user_id', $user->id)
                    ->selectRaw('SUM(quantity) as total_quantity')
                    ->first();

                $quantity = $this->input('defective_quantity_due_to_acidity') +
                    $this->input('defective_quantity_due_to_density') +
                    $this->input('defective_quantity_due_to_impurities') +
                    $this->input('defective_quantity_due_to_coagulation');
                $returnData = ReturnTheQuantity::where('user_id', $user->id)
                    ->where('id', '!=', $this->input('id'))
                    ->whereIn('return_to', ['association', 'institution'])
                    ->selectRaw('return_to, SUM(quantity) as quantity')
                    ->groupBy('return_to')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->return_to => $item->quantity];
                    });

                $returnToAssociation = $returnData['association'] ?? 0;
                $returnToInstitution = $returnData['institution'] ?? 0;

                $quantity += $returnToAssociation + $returnToInstitution;

                if ($quantity > $receiptFromAssociation->total_quantity) {
                    $validator->errors()->add('association_id', 'لا يوجد لديك الكمية');
                }
                /// نهاية كود التحقق من الكميات
                if ($returnTo == 'association') {
                    if (is_null($receiptFromAssociationId))
                        $validator->errors()->add('association_id', 'معرف الجمعية مطلوب');

                    $association = User::where('id', $receiptFromAssociationId)
                        ->where('user_type', 'association')->first();
                    if (!$association)
                        $validator->errors()->add('association_id', ' الجمعية غير موجودة');
                }
            } else {
                $validator->errors()->add('id', 'العملية غير موجودة');
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
            'return_to.required' => 'الجهة المحول لها مطلوبة',
            'return_to.in' => 'الجهة المحول لها يجب ان تكون institution او association',
            'association_id.exists' => 'معرف الجمعية مطلوب',
            'defective_quantity_due_to_coagulation.required' => 'الكمية التالفة بسبب التخثر مطلوبة',
            'defective_quantity_due_to_coagulation.numeric' => 'الكمية التالفة بسبب التخثر يجب أن تكون رقمية',
            'defective_quantity_due_to_coagulation.min' => 'الكمية التالفة بسبب التخثر يجب أن تكون على الأقل 0',
            'defective_quantity_due_to_impurities.required' => 'الكمية التالفة بسبب الشوائب مطلوبة',
            'defective_quantity_due_to_impurities.numeric' => 'الكمية التالفة بسبب الشوائب يجب أن تكون رقمية',
            'defective_quantity_due_to_impurities.min' => 'الكمية التالفة بسبب الشوائب يجب أن تكون على الأقل 0',
            'defective_quantity_due_to_density.required' => 'الكمية التالفة بسبب الكثافة مطلوبة',
            'defective_quantity_due_to_density.numeric' => 'الكمية التالفة بسبب الكثافة يجب أن تكون رقمية',
            'defective_quantity_due_to_density.min' => 'الكمية التالفة بسبب الكثافة يجب أن تكون على الأقل 0',
            'defective_quantity_due_to_acidity.required' => 'الكمية التالفة بسبب الحموضة مطلوبة',
            'defective_quantity_due_to_acidity.numeric' => 'الكمية التالفة بسبب الحموضة يجب أن تكون رقمية',
            'defective_quantity_due_to_acidity.min' => 'الكمية التالفة بسبب الحموضة يجب أن تكون على الأقل 0',
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
