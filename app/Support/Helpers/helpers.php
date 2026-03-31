<?php

declare(strict_types=1);

use App\Support\Tool\Crm\CrmAuth;

if (!function_exists('crm')) {

    /**
     * 取得當前登入資料
     *
     * @param string|int|null $parameters
     *
     * @return \App\Support\Tool\Crm\CrmAuth|mixed
     */
    function crm(string|int|null $parameters = null)
    {
        if (func_num_args() === 0) {
            return new class () {
                public function __call($method, $parameters)
                {
                    return CrmAuth::$method(...$parameters);
                }
            };
        }

        return CrmAuth::user($parameters);
    }
}
