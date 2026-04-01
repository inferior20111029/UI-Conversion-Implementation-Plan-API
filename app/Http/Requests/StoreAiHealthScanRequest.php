<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAiHealthScanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:10240'],
            'pet_id' => [
                'required',
                'integer',
                Rule::exists('pets', 'id')->where(fn ($query) => $query->where('user_id', $this->user()?->id)),
            ],
        ];
    }

    public function messages()
    {
        return [
            'file.required' => '請上傳寵物照片',
            'file.image' => '上傳檔案必須為圖片',
            'file.mimes' => '圖片格式僅支援 JPG、JPEG、PNG',
            'file.max' => '圖片大小不可超過 10MB',
            'pet_id.required' => '請提供寵物 ID',
            'pet_id.exists' => '寵物資料不存在',
        ];
    }
}
