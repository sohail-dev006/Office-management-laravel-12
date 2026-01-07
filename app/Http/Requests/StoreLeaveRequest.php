<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreLeaveRequest extends FormRequest
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
        // return [
        //     'employee_id' => 'required|exists:employees,id',
        //     'start_date' => 'required|date|in:' . Carbon::now('Asia/Karachi')->toDateString(),
        //     'end_date' => 'required|date|after_or_equal:start_date',
        //     'days_requested' => 'required|numeric',
        //     'leave_type' => 'required|string|in:Casual,Sick,Earned,Holiday',
        //     'reason' => 'required|string',
        //     'status' => 'required|in:Pending,Approved,Rejected',
        // ];
        return [
        'employee_id' => 'required|exists:employees,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'days_requested' => 'required|numeric',
        'reason' => 'required|string',
        'leave_type' => 'required|string',
        ];
    }
}
