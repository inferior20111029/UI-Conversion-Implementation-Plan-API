<?php

namespace App\Support\Trait\Excel;

trait VerifyExportTrait
{
    /**
     * @param  bool  $isVertical
     *
     * @return $this
     */
    public function asVertical(bool $isVertical): self
    {
        $this->isVertical = $isVertical;

        return $this;
    }

    /**
     * @param  bool  $isHorizontal
     *
     * @return $this
     */
    public function asHorizontal(bool $isHorizontal): self
    {
        $this->isHorizontal = $isHorizontal;

        return $this;
    }

    /**
     * 合併標題
     *
     * @param  bool  $isMergeTitle
     *
     * @return $this
     */
    public function asMergeTitle(bool $isMergeTitle): self
    {
        $this->isMergeTitle = $isMergeTitle;

        return $this;
    }

    /**
     * 下拉選單(單層)
     *
     * @param  bool  $isSelectMenu
     *
     * @return $this
     */
    public function asSelectMenu(bool $isSelectMenu): self
    {
        $this->isSelectMenu = $isSelectMenu;

        return $this;
    }

    /**
     * 資料格式(提是)
     *
     * @param  bool  $isSelectMenu
     *
     * @return $this
     */
    public function asDateFormat(bool $isDateFormat): self
    {
        $this->isDateFormat = $isDateFormat;

        return $this;
    }

    /**
     * 標題顏色
     *
     * @param  bool  $isHeadColor
     *
     * @return $this
     */
    public function asHeadColor(bool $isHeadColor): self
    {
        $this->isHeadColor = $isHeadColor;

        return $this;
    }

    /**
     * 顏色框
     *
     * @param  bool  $isStyleBox
     *
     * @return $this
     */
    public function asStyleBox(bool $isStyleBox): self
    {
        $this->isStyleBox = $isStyleBox;

        return $this;
    }

    /**
     * 注釋
     *
     * @param  bool  $isCommentToCell
     *
     * @return $this
     */
    public function asCommentToCell(bool $isCommentToCell): self
    {
        $this->isCommentToCell = $isCommentToCell;

        return $this;
    }

    /**
     * 車位特殊公式用途
     *
     * @param  bool  $carCalculationFormula
     *
     * @return $this
     */
    public function asCarCalculationFormula(bool $carCalculationFormula): self
    {
        $this->carCalculationFormula = $carCalculationFormula;

        return $this;
    }

    /**
     * 設定各式
     *
     * @param  array  $isColumnFormat
     *
     * @return self
     */
    public function asColumnFormat(bool $isColumnFormat): self
    {
        $this->isColumnFormat = $isColumnFormat;

        return $this;
    }
}