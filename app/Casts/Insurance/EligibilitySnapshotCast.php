<?php

namespace App\Casts\Insurance;

use App\Support\Insurance\Data\EligibilitySnapshotData;

class EligibilitySnapshotCast extends DataObjectCast
{
    protected function dataClass(): string
    {
        return EligibilitySnapshotData::class;
    }
}
