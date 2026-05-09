<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public registration
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'pf_number' => 'required|string|max:50|unique:employees,pf_number',
            'nic' => 'required|string|max:20|unique:employees,nic',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email|unique:employees,email',
            'password' => 'required|string|min:8|confirmed',
            'date_of_birth' => 'required|date|before:today',
            'grade' => 'required|string|max:100',
            'current_designation' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'unit_id' => 'required|exists:units,id',
            'date_of_first_appointment' => 'nullable|date',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'pf_number' => 'PF Number',
            'nic' => 'NIC',
            'date_of_birth' => 'Date of Birth',
            'current_designation' => 'Current Designation',
            'contact_number' => 'Contact Number',
            'unit_id' => 'Unit/Department',
        ];
    }
}
