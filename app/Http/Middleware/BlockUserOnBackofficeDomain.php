<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockUserOnBackofficeDomain
{
    /**
     * Handle an incoming request.
     * Blocks user routes from accessing the backoffice domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $portalDomain = config('app.portal_domain');
        if ($portalDomain && $request->getHost() === $portalDomain) {
            return redirect()->to(rtrim(config('app.url'), '/'));
        }
        return $next($request);
    }
}
