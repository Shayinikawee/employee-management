<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = $this->route('employee')?->id;

        return [
            'name' => 'required|string|max:255',
            'pf_number' => 'required|string|max:50|unique:employees,pf_number,' . $employeeId,
            'address' => 'required|string|max:500',
            'email' => 'required|email|max:255|unique:employees,email,' . $employeeId,
            'nic' => [
                'required',
                'string',
                'regex:/^([0-9]{9}[vVxX]|[0-9]{12})$/',
                'unique:employees,nic,' . $employeeId,
            ],
            'contact_number' => 'required|string|max:15',
            'date_of_birth' => 'required|date|before:today',
            'grade' => 'required|string|max:50',
            'current_designation' => 'required|string|max:255',
            'date_of_first_appointment' => 'required|date',
            'date_of_confirmation' => 'nullable|date',
            'work_histories' => 'nullable|array',
            'work_histories.*.from_date' => 'required_with:work_histories.*.position|date',
            'work_histories.*.to_date' => 'nullable|date|after_or_equal:work_histories.*.from_date',
            'work_histories.*.position' => 'required_with:work_histories.*.from_date|string|max:255',
            'work_histories.*.organization' => 'required_with:work_histories.*.from_date|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nic.regex' => 'NIC must be in valid Sri Lankan format (9 digits + V/X or 12 digits).',
        ];
    }
}
