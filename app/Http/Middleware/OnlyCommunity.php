<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

use App\Services\Auth\PassportService;

class OnlyCommunity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        (new PassportService())->onlyCommunity($request);
        return $next($request);
    }
}
