<?php

declare(strict_types=1);

namespace App\Support\Enum;

enum ComponentFilesType: string
{
    use \App\Support\Trait\Enum\Convert;

    case FILE_BIM = 'file_bim'; // BIM圖資

    case FILE_SHOP_DRAWING = 'file_shop_drawing'; // 施工圖

    case FILE_BUILT_DRAWING = 'file_built_drawing'; // 竣工圖

    case CONSERVATION_INSTRUCTIONS = 'conservation_instructions'; // 保養說明書

    case USER_GUIDE = 'user_guide'; // 設備說明書/操作手冊

    case CERTIFICATE_OF_MERCHANDISE = 'certificate_of_merchandise'; // 出廠報告

    case TESTING_REPORT = 'testing_report'; // 測試報告

    case OTHER = 'other'; // 廠商保固養護紀錄

    case SPECIFICATIONS = 'specifications'; // 設備規格書

    case CERTIFICATION_REPORT = 'certification_report'; // 設備規格書

    case MATERIALS_COST_LIST = 'materials_cost_list'; // 保養材料費用清單

    case FLOOR_PLAN = 'floor_plan'; // 樓層平面圖

    case BUILDING_ELEVATION = 'building_elevation'; // 樓層平面圖

    case LIGHTING_SCHEME = 'lighting_scheme'; // 樓層平面圖

    case ENERGY_LOSS_ESTIMATE = 'energy_loss_estimate'; // 建築物能源損耗預估(水費, 電費)

    case IMAGES = 'images'; // 圖片
}
