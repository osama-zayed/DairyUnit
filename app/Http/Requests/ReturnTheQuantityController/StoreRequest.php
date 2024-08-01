<?php

namespace App\Http\Requests\ReturnTheQuantityController;

use App\Models\ReceiptFromAssociation;
use App\Models\ReturnTheQuantity;
use App\Models\TransferToFactory;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            $returnTo = $this->input('return_to');
            $receiptFromAssociationId = $this->input('association_id');

            $receiptFromAssociation = ReceiptFromAssociation::where('user_id', $user->id)
                ->selectRaw('SUM(quantity) as total_quantity')
                ->first();

            $quantity = $this->input('defective_quantity_due_to_acidity') +
                $this->input('defective_quantity_due_to_density') +
                $this->input('defective_quantity_due_to_impurities') +
                $this->input('defective_quantity_due_to_coagulation');
                $returnData = ReturnTheQuantity::where('user_id', $user->id)
                ->selectRaw('return_to, SUM(quantity) as quantity')
                ->get();
                
                $quantity+= $returnData->quantity ;

            if ($quantity > $receiptFromAssociation->total_quantity) {
                $validator->errors()->add('association_id', 'لا يوجد لديك الكمية');
            }
            
            if ($returnTo == 'association') {
                if (is_null($receiptFromAssociationId))
                    $validator->errors()->add('association_id', 'معرف الجمعية مطلوب');

                $association = User::where('id', $receiptFromAssociationId)
                    ->where('user_type', 'association')->first();
                if (!$association)
                    $validator->errors()->add('association_id', ' الجمعية غير موجودة');
            }
        });
    }
    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
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
