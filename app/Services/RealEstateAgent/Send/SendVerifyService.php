<?php

declare(strict_types=1);

namespace App\Services\RealEstateAgent\Send;

use Illuminate\Support\Facades\Mail;

use App\Support\Abstract\Service;
use App\Support\Tool\Url\UrlMagic;
use App\Support\Enum\SendMessage;
use App\Support\Enum\RealEstateAgentTokenType;

use App\Models\RealEstateAgent;

use App\Mail\RealEstateAgentVerifyEmail;

final class SendVerifyService extends Service
{
    use \App\Support\Trait\RealEstateAgent\ColumnTrait;
    use \App\Support\Trait\RealEstateAgent\VerifyTrait;
    use \App\Support\Trait\RealEstateAgent\CreateTokenTrait;
    use \App\Support\Trait\RealEstateAgent\NotificationTrait;

    /**
     * 發送帳號驗證信件
     *
     * @param \App\Models\RealEstateAgent $realEstateAgent 房仲資料
     *
     * @return void
     */
    public function execute(RealEstateAgent $realEstateAgent): void
    {
        $this->canVerify($realEstateAgent);

        $tokenColumnData = $this->fetchTokenColumnData(type: RealEstateAgentTokenType::verify->name);

        $token = $tokenColumnData->getToken();
        $shortUrl = $this->fetchShortUrl($token);

        $this->createTokenData($realEstateAgent, $tokenColumnData);
        $this->sendMail($realEstateAgent, $shortUrl);
    }

    /**
     * 取得短連結
     *
     * @param string $token
     *
     * @return string
     */
    private function fetchShortUrl(string $token): string
    {
        return UrlMagic::short(
            route('estateVue.verificationPage', compact('token'))
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

        Mail::to($email)->send(new RealEstateAgentVerifyEmail($shortUrl));
    }
}
