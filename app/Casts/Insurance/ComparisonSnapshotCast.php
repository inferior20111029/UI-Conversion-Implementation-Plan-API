<?php

namespace App\Casts\Insurance;

use App\Support\Insurance\Data\ComparisonSnapshotData;

class ComparisonSnapshotCast extends DataObjectCast
{
    protected function dataClass(): string
    {
        return ComparisonSnapshotData::class;
    }
}
