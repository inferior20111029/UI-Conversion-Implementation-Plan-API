<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\Models\RealEstateAgent;

use App\Support\Abstract\DataParameter;

final class RealEstateAgentData extends DataParameter implements DataInterface
{
    /**
     * 識別碼
     * @var string|null
     */
    private ?string $identificationCode = null;

    /**
     * 頭像, file_id
     *
     * @var integer
     */
    private int $avatar = 0;

    /**
     * 名字
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * 性別
     *
     * @var string|null
     */
    private ?string $sex = null;

    /**
     * 生日
     *
     * @var string|null
     */
    private ?string $birthday = null;

    /**
     * 身分證字號
     *
     * @var string|null
     */
    private ?string $nationalIdNumber = null;

    /**
     * 手機號碼-區碼
     *
     * @var string|null
     */
    private ?string $cellphoneAreaCode = null;

    /**
     * 手機號碼
     *
     * @var string|null
     */
    private ?string $cellphone = null;

    /**
     * 聯絡電話-區碼
     *
     * @var string|null
     */
    private ?string $contactNumbersAreaCode = null;

    /**
     * 聯絡電話
     *
     * @var string|null
     */
    private ?string $contactNumbers = null;

    /**
     * 電子信箱
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * 公司電話-區碼
     *
     * @var string|null
     */
    private ?string $companyCellphoneAreaCode = null;

    /**
     * 公司電話
     *
     * @var string|null
     */
    private ?string $companyCellphone = null;

    /**
     * 公司名稱
     *
     * @var string|null
     */
    private ?string $companyName = null;

    /**
     * 公司分店名稱
     *
     * @var string|null
     */
    private ?string $companyBranchName = null;

    /**
     * 公司地址
     *
     * @var string|null
     */
    private ?string $companyAddress = null;

    /**
     * 公司 URL
     *
     * @var string|null
     */
    private ?string $companyUrl = null;

    /**
     * 驗證狀態 0:未驗證, 1:已驗證
     *
     * @var integer
     */
    private int $verifyState = 0;

    /**
     * 刪除者 user_id
     *
     * @var integer
     */
    private int $deleteBy = 0;

    /**
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        foreach ($params as $key => $value) {
            $this->{str($key)->camel()->value} = $value;
        }
    }

    public function toColumnArray(): array
    {
        $column = $this->fetchColumn();
        return $this->columnHandle($column);
    }

    public function fetchColumn(): array
    {
        $fillable = (new RealEstateAgent())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
