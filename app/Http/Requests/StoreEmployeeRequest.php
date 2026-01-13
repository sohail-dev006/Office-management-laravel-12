<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
        $employeeId = $this->route('employee') ?->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'designation'       => ['required', 'string', 'max:255'],
            'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email'),],
            // 'email'      => [
            //     'required',
            //     'email',
            //     'max:255',
            //     Rule::unique('employees')->ignore($employeeId),
            // ],
            'password'   => [$employeeId ? 'nullable' : 'required', 'string', 'min:8',],
            'phone'      => ['required', 'string', 'max:20'],
            'address'    => ['required', 'string', 'max:255'],
            'gender'     => ['required', Rule::in(['male','female',])],
            'dob'        => ['required', 'date', 'before:today'],
            'join_date'  => ['required', 'date'],
            'job_type'   => ['required', 'string', 'max:255'],
            'city'       => ['required', 'string', 'max:255'],
            'salary'     => ['required', 'numeric', 'min:0'],
            'status'     => ['required', Rule::in(['active','inactive'])],
            'age'        => ['required', 'integer'],
            // 'min:18'
            'documents'               => 'nullable|array',
'documents.*.type'        => 'required|string',
'documents.*.file'        => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',

            
        ];
    }
}
