<?php

declare(strict_types=1);

namespace App\Services\Auth\Frontend;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Abstract\Service;
use App\Support\Enum\LoginMessage;

use App\Http\Requests\Auth\LoginRequest;

use App\Models\Login;

final class LoginService extends Service
{
    /**
     * 使用者登入
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return string
     */
    public function execute(LoginRequest $request): string
    {
        return $this->verifyUser($request);
    }

    /**
     * 驗證使用者
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @throws \App\Exceptions\ApiException
     * @return string
     */
    private function verifyUser(LoginRequest $request): string
    {
        $credentials = $request->only('account', 'password');
        $token = (string) auth()->attempt($credentials);

        if (empty($token)) {
            $this->fails(LoginMessage::FAILS->value, Response::HTTP_UNAUTHORIZED);
        }

        /** @var Login */
        $user = auth()->user();

        if (
            !$user->loginRealEstateAgent()->exists()
            &&
            !$user->loginTable()->exists()
        ) {
            $this->fails(LoginMessage::FAILS->value, Response::HTTP_UNAUTHORIZED);
        }

        return $token;
    }
}
