<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if(Auth::user()->getRole() == 'Customer'){
                return $next($request);
            }
            if(Auth::user()->getRole() == 'Super Admin'){
                return redirect(route("admin.dashboard"));
            }
        } else {
            Auth::logout();
            return redirect(route('frontend.showLogin'));
        }
    }
}
