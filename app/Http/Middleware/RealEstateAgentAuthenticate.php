<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use App\Support\Enum\AccessMessage;
use App\Support\Response\ApiMessage;

use App\Models\RealEstateAgent;

use App\Repositories\RealEstateAgent\RealEstateAgentRepository;

class RealEstateAgentAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $realEstateAgent = $this->fetchRealEstateAgent($request);
        $request->merge(['realEstateAgent' => $realEstateAgent]);

        return $next($request);
    }

    /**
     * 取得仲介資料
     *
     * @param \Illuminate\Http\Request $request
     * @throws \App\Exceptions\ApiException
     *
     * @return \App\Models\RealEstateAgent
     */
    private function fetchRealEstateAgent(Request $request): RealEstateAgent
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $realEstateAgent = (new RealEstateAgentRepository())->findByToken($token);

        if (!empty($realEstateAgent)) {
            return $realEstateAgent;
        }

        (new ApiMessage())->throwException(AccessMessage::tokenInvalid->value, Response::HTTP_FORBIDDEN);
    }
}
