<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthRecordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:weight,vaccine,checkup',
            'value' => 'nullable|string',
            'recorded_at' => 'nullable|date',
        ];
    }
}
