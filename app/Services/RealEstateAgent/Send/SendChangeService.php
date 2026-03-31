<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Send;

use Illuminate\Support\Facades\Mail;

use App\Support\Abstract\Service;

use App\Support\Tool\Url\UrlMagic;

use App\Support\Enum\SendMessage;
use App\Support\Enum\RealEstateAgentTokenType;
use App\Support\Data\RealEstateAgentTokenData;

use App\Models\RealEstateAgent;

use App\Mail\RealEstateAgentChangePassword;

final class SendChangeService extends Service
{
    use \App\Support\Trait\RealEstateAgent\ColumnTrait;
    use \App\Support\Trait\RealEstateAgent\CreateTokenTrait;
    use \App\Support\Trait\RealEstateAgent\NotificationTrait;

    /**
     * 發送修改密碼信件或簡訊
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     *
     * @return void
     */
    public function execute(RealEstateAgent $realEstateAgent): void
    {
        $tokenColumnData = $this->fetchTokenColumnData(RealEstateAgentTokenType::changePassword->name);

        $shortUrl = $this->fetchShortUrl($tokenColumnData);

        $this->sendMail($realEstateAgent, $shortUrl);
        $this->createTokenData($realEstateAgent, $tokenColumnData);
    }

    /**
     * 取得短連結
     *
     * @param RealEstateAgentTokenData $tokenColumnData
     *
     * @return string
     */
    private function fetchShortUrl(RealEstateAgentTokenData $tokenColumnData): string
    {
        $token = $tokenColumnData->getToken();
        $expires = $tokenColumnData->getExpiresAt()->timestamp;

        return UrlMagic::short(
            route('estateVue.passwordChange', compact('token', 'expires'))
        );
    }

    /**
     * 發送郵件
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent
     * @param string $shortUrl
     * @throws \App\Exceptions\ApiException
     *
     * @return void
     */
    private function sendMail(RealEstateAgent $realEstateAgent, string $shortUrl): void
    {
        $email = $realEstateAgent->email;

        if (empty($email)) {
            $this->fails(SendMessage::EMPTY_EMAIL->value);
        }

        Mail::to($email)->send(new RealEstateAgentChangePassword($shortUrl));
    }
}
