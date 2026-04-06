<?php

namespace App\Casts\Insurance;

use App\Support\Insurance\Data\ClaimRequirementSnapshotData;

class ClaimRequirementSnapshotCast extends DataObjectCast
{
    protected function dataClass(): string
    {
        return ClaimRequirementSnapshotData::class;
    }
}
