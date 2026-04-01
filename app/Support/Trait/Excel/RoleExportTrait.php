<?php

namespace App\Support\Trait\Excel;

use Illuminate\Support\Facades\Validator;

trait RoleExportTrait
{
    /**
     * 判斷組態名稱長度
     *
     * @param $config
     */
    public function judgeConfigNameLength($configData)
    {
        $rules = [
            'district'  => 'sometimes|required|max:64',
            'building'  => 'sometimes|required|max:64',
            'staircase' => 'sometimes|required|max:64',
            'floor'     => 'sometimes|required|max:64',
            'household' => 'sometimes|required|max:64',
        ];

        $v = Validator::make($configData, $rules);

        return $v->fails();
    }
}
