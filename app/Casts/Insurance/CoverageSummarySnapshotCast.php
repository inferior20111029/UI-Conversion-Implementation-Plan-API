<?php

namespace App\Casts\Insurance;

use App\Support\Insurance\Data\CoverageSummarySnapshotData;

class CoverageSummarySnapshotCast extends DataObjectCast
{
    protected function dataClass(): string
    {
        return CoverageSummarySnapshotData::class;
    }
}
