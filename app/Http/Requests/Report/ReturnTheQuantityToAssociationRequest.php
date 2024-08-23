<?php

namespace App\Http\Requests\Report;

use App\Models\Driver;
use App\Models\Family;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReturnTheQuantityToAssociationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date_and_time' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ],
            'end_date_and_time' => [
                'required',
                'date_format:Y-m-d H:i:s'
            ],
            'association_id' => ['nullable', 'exists:users,id'],
 
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $startdateOfCollection = $this->input('start_date_and_time');
            $enddateOfCollection = $this->input('end_date_and_time');
            $now = \Carbon\Carbon::now();
            $start = \Carbon\Carbon::parse($startdateOfCollection);
            $end = \Carbon\Carbon::parse($enddateOfCollection);
            if ($end->lessThan($now)) {
                $validator->errors()->add('start_date_and_time', 'يجب أن لا يكون تاريخ ووقت انتهاء التقرير بعد الوقت الحالي');
            }
            if ($start->lessThan($now)) {
                $validator->errors()->add('end_date_and_time', 'يجب أن لا يكون تاريخ ووقت بدء التقرير بعد الوقت الحالي.');
            }
            if ($end->lessThan($start)) {
                $validator->errors()->add('end_date_and_time', 'يجب أن يكون تاريخ ووقت انتهاء التقرير بعد تاريخ ووقت بدء التقرير.');
            }
        });
    }
    public function messages()
    {
        return [
            'start_date_and_time.required' => 'وقت بدء التقرير مطلوب (من)',
            'start_date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت التقرير صالحًا',
            'end_date_and_time.required' => 'وقت انتهاء التقرير مطلوب (الى)',
            'end_date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت التقرير صالحًا',
            'association_id.required' => 'معرف الجمعية مطلوب',
            'association_id.exists' => 'الجمعية المحدد غير موجود',
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
