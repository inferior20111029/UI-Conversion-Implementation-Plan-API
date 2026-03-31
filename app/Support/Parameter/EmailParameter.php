<?php

declare(strict_types=1);

namespace App\Support\Parameter;

class EmailParameter
{
    /**
     * 公司 ID
     *
     * @var integer
     */
    private int $companyId = 0;

    /**
     * Email 標題
     *
     * @var string
     */
    private string $title = '';

    /**
     * 信件內容
     *
     * @var string
     */
    private string $content = '';

    /**
     * 收件人 email
     *
     * @var array
     */
    private array $mailTo = [];

    /**
     * 附件 (網址)
     *
     * @var array
     */
    private array $attach = [];

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
