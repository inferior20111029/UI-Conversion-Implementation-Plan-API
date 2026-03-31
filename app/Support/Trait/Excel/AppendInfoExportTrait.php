<?php

namespace App\Support\Trait\Excel;

trait AppendInfoExportTrait
{
    /**
     * Append additional data to the collection.
     *
     * @param array $collectionData
     * @return $this
     */
    public function appendCollectionData(array $collectionData): self
    {
        $this->collectionData = $collectionData;
        return $this;
    }

    /**
     * Head Color
     *
     * @param  array  $data
     *
     * @return self
     */
    public function appendHeadColorParameter(array $data): self
    {
        $this->headColorParameter = $data;

        return $this;
    }

    /**
     * Column Width
     *
     * @param  float  $data
     *
     * @return $this
     */
    public function appendColumnWidth(float $data): self
    {
        $this->colWidth = $data;

        return $this;
    }

    /**
     * Column Height
     *
     * @param  float  $data
     *
     * @return $this
     */
    public function appendColumnHeight(float $data): self
    {
        $this->colHeight = $data;

        return $this;
    }

    /**
     * 紅框提醒
     * @param  array  $data
     *
     * @return $this
     */
    public function appendStyleRedBox(array $data): self
    {
        $this->styleRedBox = $data;

        return $this;
    }

    /**
     * @param  array  $data
     *
     * @return $this
     */
    public function appendCommentToCell(array $data): self
    {
        $this->commentToCell = $data;

        return $this;
    }

    /**
     * @param  array  $data
     *
     * @return self
     */
    public function appendSelectMenuParameter(array $data): self
    {
        $this->selectMenuParameter = $data;

        return $this;
    }

    /**
     * @param  array  $data
     *
     * @return self
     */
    public function appendDateFormatParameter(array $data): self
    {
        $this->selectDateFormatParameter = $data;

        return $this;
    }

    /**
     * @param  array  $data
     *
     * @return self
     */
    public function appendMergeTitleToCol(array $data, $borderRange = []): self
    {
        $this->mergeTitleToCol = $data;

        $this->setBorderRange  = $borderRange;

        return $this;
    }

    /**
     * @param  array  $data
     *
     * @return self
     */
    public function applyColumnFormat(array $data): self
    {
        $this->columnFormat = $data;

        return $this;
    }
}
