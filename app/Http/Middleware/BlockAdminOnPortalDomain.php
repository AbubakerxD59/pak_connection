<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockAdminOnPortalDomain
{
    /**
     * Handle an incoming request.
     * Blocks admin routes when accessed from the portal domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $portalDomain = config('app.portal_domain');

        if ($portalDomain && $request->getHost() === $portalDomain) {
            abort(404);
        }

        return $next($request);
    }
}
