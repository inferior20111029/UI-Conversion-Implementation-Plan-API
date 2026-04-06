<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('breed')) {
            $this->merge([
                'breed' => $this->normalizeNullableString($this->input('breed')),
            ]);
        }

        if ($this->has('microchip_number')) {
            $this->merge([
                'microchip_number' => $this->normalizeNullableString($this->input('microchip_number'), true),
            ]);
        }

        if ($this->has('registration_number')) {
            $this->merge([
                'registration_number' => $this->normalizeNullableString($this->input('registration_number'), true),
            ]);
        }

        if ($this->has('is_registered') || $this->has('registration_number')) {
            $isRegistered = $this->has('is_registered')
                ? $this->normalizeBoolean($this->input('is_registered'))
                : null;
            $registrationNumber = $this->input('registration_number');

            $this->merge([
                'is_registered' => $isRegistered ?? ($registrationNumber !== null),
                'registration_number' => ($isRegistered === false && $registrationNumber === null)
                    ? null
                    : ($isRegistered === false ? null : $registrationNumber),
            ]);
        }
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:dog,cat',
            'gender' => 'nullable|in:male,female',
            'breed' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'weight' => 'nullable|numeric|min:0',
            'microchip_number' => 'nullable|string|max:64',
            'is_registered' => 'nullable|boolean',
            'registration_number' => 'nullable|string|max:64',
        ];
    }

    private function normalizeNullableString(mixed $value, bool $uppercase = false): ?string
    {
        $normalized = trim((string) $value);

        if ($normalized === '') {
            return null;
        }

        return $uppercase ? strtoupper($normalized) : $normalized;
    }

    private function normalizeBoolean(mixed $value): ?bool
    {
        if ($value === null || $value === '') {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
