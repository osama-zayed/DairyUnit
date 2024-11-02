<?php

namespace App\Http\Requests\Family;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddFamilyRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{9}$/|unique:families,phone',
            'local_cows_producing' => 'required|integer|min:0',
            'local_cows_non_producing' => 'required|integer|min:0',
            'born_cows_producing' => 'required|integer|min:0',
            'born_cows_non_producing' => 'required|integer|min:0',
            'imported_cows_producing' => 'required|integer|min:0',
            'imported_cows_non_producing' => 'required|integer|min:0',
            'governorate_id' => 'required|exists:governorates,id',
            'directorate_id' => 'required|exists:directorates,id',
            'isolation_id' => 'required|exists:isolations,id',
            'village_id' => 'required|exists:villages,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'الاسم يجب أن يكون نصًا',
            'name.max' => 'الاسم لا يمكن أن يتجاوز 255 حرفًا',
            'phone.required' => 'رقم الهاتف مطلوب',
            'phone.regex' => 'رقم الهاتف يجب أن يكون 9 أرقام',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل',
            'local_cows_producing.required' => 'عدد الأبقار المنتجة مطلوب',
            'local_cows_producing.integer' => 'عدد الأبقار المنتجة يجب أن يكون رقمًا',
            'local_cows_non_producing.required' => 'عدد الأبقار غير المنتجة مطلوب',
            'local_cows_non_producing.integer' => 'عدد الأبقار غير المنتجة يجب أن يكون رقمًا',
            'born_cows_producing.required' => 'عدد الأبقار المولدة المنتجة مطلوب',
            'born_cows_producing.integer' => 'عدد الأبقار المولدة المنتجة يجب أن يكون رقمًا',
            'born_cows_non_producing.required' => 'عدد الأبقار المولدة غير المنتجة مطلوب',
            'born_cows_non_producing.integer' => 'عدد الأبقار المولدة غير المنتجة يجب أن يكون رقمًا',
            'imported_cows_producing.required' => 'عدد الأبقار الخارجية المنتجة مطلوب',
            'imported_cows_producing.integer' => 'عدد الأبقار الخارجية المنتجة يجب أن يكون رقمًا',
            'imported_cows_non_producing.required' => 'عدد الأبقار الخارجية غير المنتجة مطلوب',
            'imported_cows_non_producing.integer' => 'عدد الأبقار الخارجية غير المنتجة يجب أن يكون رقمًا',
            'governorate_id.required' => 'معرف المحافظة مطلوب',
            'governorate_id.exists' => 'المحافظة المحددة غير موجودة',
            'directorate_id.required' => 'معرف المديرية مطلوب',
            'directorate_id.exists' => 'المديرية المحددة غير موجودة',
            'isolation_id.required' => 'معرف العزلة مطلوب',
            'isolation_id.exists' => 'العزلة المحددة غير موجودة',
            'village_id.required' => 'معرف القرية مطلوب',
            'village_id.exists' => 'القرية المحددة غير موجودة',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
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