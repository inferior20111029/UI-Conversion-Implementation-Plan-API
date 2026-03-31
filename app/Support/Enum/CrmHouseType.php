<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum CrmHouseType: string
{
    use \App\Support\Trait\Enum\Convert;

    case H001 = '住家用';

    case H002 = '客房';

    case H004 = '店鋪';

    case H005 = '商場';

    case H006 = '辦公室';

    case H007 = '合併戶';

    case H008 = '汽車停車位（法定）';

    case H009 = '汽車停車場（獎勵）';

    case H010 = '汽車停車場（增設）';

    case H011 = '機車停車場（法定）';

    case H012 = '機車停車場（獎勵）';

    case H013 = '機車停車場（增設）';

    case H014 = '公共空間';

    public function isHouse()
    {
        if (in_array($this, $this->houseCode())) {
            return true;
        }

        return false;
    }

    public function isCarParking()
    {
        if (in_array($this, $this->carParkingCode())) {
            return true;
        }

        return false;
    }

    public function houseCode()
    {
        return [
            CrmHouseType::H001,
            CrmHouseType::H002,
            CrmHouseType::H004,
            CrmHouseType::H005,
            CrmHouseType::H006,
            CrmHouseType::H007,
            CrmHouseType::H014
        ];
    }

    public function carParkingCode()
    {
        return [
            CrmHouseType::H008,
            CrmHouseType::H009,
            CrmHouseType::H010,
            CrmHouseType::H011,
            CrmHouseType::H012,
            CrmHouseType::H013
        ];
    }
}
