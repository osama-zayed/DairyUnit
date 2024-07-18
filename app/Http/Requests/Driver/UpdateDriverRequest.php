<?php

namespace App\Http\Requests\Driver;

use App\Models\Driver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateDriverRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:drivers,id', function ($attribute, $value, $fail) {
                $driver = Driver::findOrFail($value);
                if ($driver->association_id !== auth('sanctum')->user()->id) {
                    $fail('لم تقم أنت بإضافة هذه السائق.');
                }
            }],
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^[0-9]{9}$/|unique:drivers,phone,' . $this->id,
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
