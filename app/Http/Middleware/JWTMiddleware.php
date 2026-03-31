<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

use App\Support\Enum\TokenMessage;
use App\Support\Response\ApiMessage;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $accessToken = $request->bearerToken();

        if (empty($accessToken)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->verifyToken($accessToken);

        auth()->setToken($accessToken)->user();

        return $next($request);
    }

    /**
     * 驗證 Token 正確性
     *
     * @param string $accessToken
     *
     * @return void
     */
    private function verifyToken(string $accessToken): void
    {
        $apiMessage = new ApiMessage();

        try {
            JWTAuth::parseToken($accessToken)->authenticate();
        } catch (TokenExpiredException) {
            $apiMessage->throwException(TokenMessage::EXPIRED->value, Response::HTTP_UNAUTHORIZED);
        } catch (TokenInvalidException) {
            $apiMessage->throwException(TokenMessage::INVALID->value, Response::HTTP_UNAUTHORIZED);
        } catch (TokenBlacklistedException) {
            $apiMessage->throwException(TokenMessage::IS_BLACK->value, Response::HTTP_UNAUTHORIZED);
        } catch (Throwable $th) {
            $apiMessage->throwException($th->getMessage(), $th->getCode(), $th);
        }
    }
}
