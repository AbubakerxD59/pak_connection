<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlockAdminOnBackofficeDomain
{
    /**
     * Handle an incoming request.
     * Blocks admin users from accessing the backoffice/portal domain.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $backofficeDomain = config('app.portal_domain');

        if ($backofficeDomain && $request->getHost() === $backofficeDomain) {
            if (Auth::check()) {
                $userRole = Auth::user()->getRole();
                if (in_array($userRole, ['Super Admin', 'Admin'])) {
                    return redirect()->to(rtrim(config('app.url'), '/') . '/login')
                        ->with('error', 'Admin access is not allowed on this domain.');
                }
            }
        }

        return $next($request);
    }
}
