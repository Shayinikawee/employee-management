<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $unitId = $this->route('unit')?->id;

        return [
            'name' => 'required|string|max:255|unique:units,name,' . $unitId,
            'description' => 'nullable|string|max:1000',
        ];
    }
}
