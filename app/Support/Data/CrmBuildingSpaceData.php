<?php

declare(strict_types=1);

namespace App\Support\Data;

use App\models\CrmBuildingSpace;

use App\Support\Abstract\DataParameter;

final class CrmBuildingSpaceData extends DataParameter implements DataInterface
{
    /**
     * 建號
     *
     * @var string|null
     */
    private ?string $blockId = null;

    /**
     * 門牌
     *
     * @var string|null
     */
    private ?string $doorplate = null;

    /**
     * 坐落
     *
     * @var string|null
     */
    private ?string $locate = null;

    /**
     * 交屋日期
     *
     * @var string|null
     */
    private ?string $handoverDate = null;

    /**
     * 格局設定 ID
     *
     * @var integer
     */
    private int $crmLayoutSettingId = 0;

    /**
     * 備註
     *
     * @var string|null
     */
    private ?string $remark = null;

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
        $fillable = (new CrmBuildingSpace())->getFillable();
        return $this->parsePropertiesToColumn($fillable, get_object_vars($this));
    }
}
