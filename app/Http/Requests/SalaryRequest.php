<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaryRequest extends FormRequest
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
            'employee_id'=>'required|exists:employees,id',
            'month'=>'required|numeric|min:1|max:12',
            'year'=>'required|numeric|min:2000',

            'basic_salary'=>'required|numeric|min:0',
            'house_allowance'=>'nullable|numeric|min:0',
            'medical_allowance'=>'nullable|numeric|min:0',
            'transport_allowance'=>'nullable|numeric|min:0',
            'other_allowance'=>'nullable|numeric|min:0',
            'bonus'=>'nullable|numeric|min:0',

            'advance_salary'=>'nullable|numeric|min:0',
            'tax'=>'nullable|numeric|min:0',
            'other_deduction'=>'nullable|numeric|min:0',
        ];
    }
}
