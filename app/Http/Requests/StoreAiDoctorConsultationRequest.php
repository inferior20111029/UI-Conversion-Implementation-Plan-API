<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAiDoctorConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => [
                'required',
                'integer',
                Rule::exists('pets', 'id')->where(fn ($query) => $query->where('user_id', $this->user()?->id)),
            ],
            'message' => ['required', 'string', 'min:4', 'max:2000'],
            'history' => ['sometimes', 'array', 'max:10'],
            'history.*.role' => ['required_with:history', 'string', 'in:user,ai'],
            'history.*.content' => ['required_with:history', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'pet_id.required' => '請先選擇要諮詢的寵物。',
            'pet_id.exists' => '找不到這隻寵物，請重新整理後再試一次。',
            'message.required' => '請先輸入症狀描述。',
            'message.min' => '症狀描述至少要 4 個字，AI 才能做基本判斷。',
            'message.max' => '症狀描述請控制在 2000 字內。',
            'history.array' => '對話歷史格式不正確。',
            'history.max' => '一次最多只支援 10 則歷史訊息。',
            'history.*.role.in' => '歷史訊息角色格式不正確。',
            'history.*.content.max' => '歷史訊息內容過長。',
        ];
    }
}
