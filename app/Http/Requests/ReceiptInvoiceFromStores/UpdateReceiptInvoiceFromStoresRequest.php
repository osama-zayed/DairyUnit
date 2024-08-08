<?php

namespace App\Http\Requests\ReceiptInvoiceFromStores;

use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\TransferToFactory;
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
                'exists:receipt_invoice_from_stores,id'
            ],
            'date_and_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $requestDateTime = \Carbon\Carbon::parse($value);
                    $now = \Carbon\Carbon::now();
                    $twoDaysAgo = \Carbon\Carbon::now()->subDays(2);

                    if ($requestDateTime->greaterThan($now)) {
                        $fail('يجب أن لا يكون تاريخ ووقت التوريد في المستقبل (بعد الوقت الحالي).');
                    }

                    if ($requestDateTime->lt($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت التوريد قبل يومين.');
                    }
                },
            ],
            'quantity' => 'required|numeric|min:1',
            'associations_branche_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail) {
                $associationsBrancheId = User::findOrFail($value);
                if ($associationsBrancheId->association_id != auth('sanctum')->user()->id) {
                    $fail('لم تقم أنت بإضافة هذا المجمع');
                }
            }],
            'nots' => 'nullable',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth('sanctum')->user();
    
            // Get the latest TransferToFactory record for the user
            $lastTransferToFactory = TransferToFactory::where('association_id', $user->id)
                ->select('updated_at')
                ->latest()
                ->first();
    
            // Get the latest ReceiptInvoiceFromStore record for the user
            $lastReceiptInvoiceFromStore = ReceiptInvoiceFromStore::where('association_id', $user->id)
                ->select('created_at')
                ->latest()
                ->first();
    
            // Get the ReceiptInvoiceFromStore record being updated
            $ReceiptInvoiceFromStore = ReceiptInvoiceFromStore::where('id', $this->input("id"))
                ->where('association_id', $user->id)
                ->first();
    
            // Check if the user is the owner of the record
            if ($ReceiptInvoiceFromStore->association_id !== auth('sanctum')->user()->id) {
                $validator->errors()->add('id', 'لم تقم انت باضافة هذه العملية');
            }
    
            // Check if the record is being updated after a later record was created
            if ($ReceiptInvoiceFromStore && (
                ($ReceiptInvoiceFromStore->created_at > $lastReceiptInvoiceFromStore->created_at) ||
                ($lastTransferToFactory !== null && $ReceiptInvoiceFromStore->created_at > $lastTransferToFactory->updated_at)
            )) {
                $validator->errors()->add('date_and_time', 'لا يمكن التعديل لأنه حصل عملية في وقت لاحق');
            }
    
            // Check if the user is trying to update the record after 2 hours of creation
            $createdAt = $ReceiptInvoiceFromStore->created_at;
            $now = now();
            $diffInHours = $now->diffInHours($createdAt);
            if ($diffInHours >= 2) {
                $validator->errors()->add('date_and_time', 'لا يمكن تعديل السجل بعد مرور ساعتين من إضافته');
            }
    
            // Calculate the available quantity in the warehouse
            $warehouseSummary = CollectingMilkFromFamily::where('user_id', $this->input('associations_branche_id'))
                ->selectRaw('SUM(quantity) as total_quantity')
                ->first();
    
            $totalDeliveredQuantity = ReceiptInvoiceFromStore::where('associations_branche_id', $this->input('associations_branche_id'))
                ->selectRaw('SUM(quantity) as total_delivered_quantity')
                ->first()->total_delivered_quantity;
    
            $availableQuantity = $warehouseSummary->total_quantity - $totalDeliveredQuantity + $ReceiptInvoiceFromStore->quantity;
    
            // Check if the user has enough quantity available
            if ($availableQuantity < $this->input('quantity')) {
                $validator->errors()->add('quantity', 'لا يوجد لدى المجمع الكمية المطلوبة');
            }
        });
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
