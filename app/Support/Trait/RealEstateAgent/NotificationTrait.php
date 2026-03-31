<?php

declare(strict_types=1);

namespace App\Support\Trait\RealEstateAgent;

use App\Models\RealEstateAgent;

use App\Support\Parameter\SmsParameter;
use App\Support\Parameter\EmailParameter;

trait NotificationTrait
{
    /**
     * 填充 Sms 發送參數
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param string $message 訊息
     *
     * @return \App\Support\Parameter\SmsParameter|null
     */
    public function smsParameter(RealEstateAgent $realEstateAgent, string $message): ?SmsParameter
    {
        if (empty($realEstateAgent->cellphone)) {
            return null;
        }

        return new SmsParameter([
            'companyId' => crm('company_id'),
            'message' => $message,
            'sendTo' => [$realEstateAgent->cellphone],
            'longSend' => 1
        ]);
    }

    /**
     * 填充 Email 發送參數
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     * @param string $title 標題
     * @param string $content 內容
     *
     * @return \App\Support\Parameter\EmailParameter|null
     */
    public function emailParameter(RealEstateAgent $realEstateAgent, string $title, string $content): ?EmailParameter
    {
        if (empty($realEstateAgent->email)) {
            return null;
        }

        return new EmailParameter([
            'companyId' => crm('company_id'),
            'title' => $title,
            'content' => $content,
            'mailTo' => [$realEstateAgent->email]
        ]);
    }
}
