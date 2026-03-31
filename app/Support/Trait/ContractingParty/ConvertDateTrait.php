<?php

namespace App\Support\Trait\ContractingParty;

use Carbon\Carbon;

trait ConvertDateTrait
{
    /**
     * 1120526 to YYYY-MM-DD
     *
     * @param  string|null  $date
     *
     * @return string|null
     */
    private function convertDate(?string $date): ?string
    {
        if (is_null($date) || strlen($date) !== 7) {
            return $date;
        }

        $year  = substr($date, 0, 3);
        $month = substr($date, 3, 2);
        $day   = substr($date, 5, 2);

        $year = 1911 + (int)$year;

        return Carbon::createFromFormat('Y-m-d', "{$year}-{$month}-{$day}")->format('Y-m-d');
    }

    /**
     *  YYYY-MM-DD to 1120526
     *
     * @param string|null $date
     * @return string|null
     */
    private function convertToRepublicDate(?string $date): ?string
    {
        if (is_null($date)) {
            return $date;
        }

        try {
            $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
            $year       = str_pad(($carbonDate->year - 1911), 3, '0', STR_PAD_LEFT);
            $month      = str_pad($carbonDate->month, 2, '0', STR_PAD_LEFT);
            $day        = str_pad($carbonDate->day, 2, '0', STR_PAD_LEFT);

            return "{$year}{$month}{$day}";
        } catch (\Exception $e) {
            return $date;
        }
    }
}
