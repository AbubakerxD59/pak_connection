<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockAdminOnPortalDomain
{
    /**
     * Handle an incoming request.
     * Blocks admin routes when accessed from the backoffice domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $backofficeDomain = config('app.backoffice_domain');

        if ($backofficeDomain && $request->getHost() === $backofficeDomain) {
            return redirect()->to(rtrim(config('app.url'), '/'));
        }

        return $next($request);
    }
}
