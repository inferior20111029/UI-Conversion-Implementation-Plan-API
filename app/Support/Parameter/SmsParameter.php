<?php

declare(strict_types=1);

namespace App\Support\Parameter;

class SmsParameter
{
    /**
     * 公司 ID
     *
     * @var integer
     */
    private int $companyId = 0;

    /**
     * 收件者 (手機號碼)
     *
     * @var array
     */
    private array $sendTo = [];

    /**
     * 訊息
     *
     * @var string
     */
    private string $message = '';

    /**
     * 是否發送長簡訊 0:否, 1:是
     *
     * @var integer
     */
    private int $longSend = 0;

    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function __get($key)
    {
        return $this->{$key};
    }
}
