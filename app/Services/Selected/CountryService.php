<?php

declare(strict_types=1);

namespace App\Services\Selected;

use Locale;

use libphonenumber\PhoneNumberUtil;

use App\Support\Abstract\Service;

final class CountryService extends Service
{
    /**
     * 取得國家代碼資料
     *
     * @return array
     */
    public function execute(): array
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $supportedRegions = $phoneUtil->getSupportedRegions();

        return array_map(function (string $region) use ($phoneUtil): array {
            return [
                'name' => [
                    'en' => Locale::getDisplayRegion('-' . $region, 'en'),
                    'tw' => Locale::getDisplayRegion('-' . $region, 'zh-TW'),
                    'cn' => Locale::getDisplayRegion('-' . $region, 'zh_CN'),
                ],
                'code' => $region,
                'phoneCode' => $phoneUtil->getCountryCodeForRegion($region)
            ];
        }, $supportedRegions);
    }
}
