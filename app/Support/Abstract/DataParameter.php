<?php

declare(strict_types=1);

namespace App\Support\Abstract;

use Illuminate\Support\Arr;

abstract class DataParameter
{
    /**
     * 排除的欄位
     *
     * @var array
     */
    protected readonly array $exclude;

    /**
     * 只取得特定欄位
     *
     * @var array
     */
    protected readonly array $only;

    /**
     * 取代資料
     *
     * @var array
     */
    protected readonly array $replace;

    /**
     * 不取得多態欄位
     *
     * @var boolean
     */
    protected bool $noHaveMacro = false;

    /**
     * 清空陣列空資料
     *
     * @var boolean
     */
    protected bool $filter = false;

    /**
     * uuid
     *
     * @var string|null
     */
    public ?string $uuid = null;

    /**
     * 開始時間
     *
     * @var string|null
     */
    public ?string $startTime = null;

    /**
     * 結束時間
     *
     * @var string|null
     */
    public ?string $endTime = null;

    /**
     * 啟用狀態 0:未啟用, 1:啟用
     *
     * @var int
     */
    public int $enableState = 0;

    /**
     * 公司 ID
     *
     * @var integer
     */
    public int $companyId = 0;

    /**
     * 建案 ID
     *
     * @var integer
     */
    public int $communityId = 0;

    /**
     * 戶別 UUID
     *
     * @var string|null
     */
    public ?string $spaceId = null;

    /**
     * 多態 model
     *
     * @var string|null
     */
    public ?string $taggableType = null;

    /**
     * 多態 ID 或是 UUID
     *
     * @var mixed
     */
    public mixed $taggableId = null;


    /**
     * 排除欄位資料
     *
     * @param array|string ...$target
     *
     * @return static
     */
    public function excludeColumn(array|string ...$target): static
    {
        $this->exclude = $this->fetchTarget($target);
        return $this;
    }

    /**
     * 只取得特定欄位資料
     *
     * @param array|string ...$target
     *
     * @return static
     */
    public function onlyColumn(array|string ...$target): static
    {
        $this->only = $this->fetchTarget($target);
        return $this;
    }

    /**
     * 取代資料
     *
     * @param array $replace
     *
     * @return static
     */
    public function replace(array $replace): static
    {
        $this->replace = $replace;
        return $this;
    }

    /**
     * 移除陣列為空值的資料
     *
     * @return static
     */
    public function filterColumn(): static
    {
        $this->filter = true;
        return $this;
    }

    /**
     * 不取得多態藍位
     *
     * @return static
     */
    public function noHaveMacro(): static
    {
        $this->noHaveMacro = true;
        return $this;
    }

    /**
     * 欄位資料處理
     *
     * @param array $column
     *
     * @return array
     */
    protected function columnHandle(array $column): array
    {
        if (!empty($this->exclude)) {
            Arr::forget($column, $this->exclude);
        }

        if (!empty($this->only)) {
            $column = Arr::only($column, $this->only);
        }

        if (true === $this->filter) {
            $column = array_filter($column);
        }

        if (true === $this->noHaveMacro) {
            Arr::forget($column, ['taggable_type', 'taggable_id']);
        }

        if (!empty($this->replace)) {
            $column = [...$column, ...$this->replace];
        }

        return (array) $column;
    }

    /**
     * 透過 model fillable 轉換出欄位資料
     *
     * @param array $fillable
     * @param array $properties
     * @return array
     */
    protected function parsePropertiesToColumn(array $fillable, array $properties): array
    {
        $column = [];
        foreach ($fillable as $value) {
            $targetKey = str($value)->camel()->value;

            if (!array_key_exists($targetKey, $properties) && !array_key_exists($value, $properties)) {
                continue;
            }

            $column[$value] = array_key_exists($value, $properties)
                ? $properties[$value]
                : $properties[$targetKey];
        }

        return $column;
    }

    /**
     * 取得目標資料
     *
     * @param array $target
     *
     * @return array
     */
    private function fetchTarget(array $target): array
    {
        if (
            (new \RecursiveArrayIterator($target))->hasChildren()
        ) {
            return current($target);
        }

        return $target;
    }
}
