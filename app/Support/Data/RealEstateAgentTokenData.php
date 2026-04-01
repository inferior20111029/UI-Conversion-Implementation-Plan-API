<?php

declare(strict_types=1);

namespace App\Support\Data;

use Illuminate\Support\Carbon;

use App\Models\RealEstateAgentToken;

use App\Support\Abstract\DataParameter;

final class RealEstateAgentTokenData extends DataParameter implements DataInterface
{
    /**
     * 房屋仲介 ID
     *
     * @var integer
     */
    private int $realEstateAgentId = 0;

    /**
     * token 類型
     * @var string|null
     */
    private ?string $type = null;

    /**
     * token
     * @var string|null
     */
    private ?string $token = null;

    /**
     * 最後使用時間
     * @var Carbon|null
     */
    private ?Carbon $lastUsedAt = null;

    /**
     * 過期時間
     * @var Carbon|null
     */
    private ?Carbon $expiresAt = null;


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
        $fillable = (new RealEstateAgentToken())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }

    /**
     * 取得 Token 資料
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * 取得過期時間
     * @return \Illuminate\Support\Carbon
     */
    public function getExpiresAt(): Carbon
    {
        return $this->expiresAt;
    }
}
