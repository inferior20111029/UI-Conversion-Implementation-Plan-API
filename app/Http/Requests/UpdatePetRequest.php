<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
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
        ];
    }
}
