<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userRole = Auth::user()->getRole();
            
            // Check if user has admin role
            if ($userRole == 'Super Admin' || $userRole == 'Admin') {
                return $next($request);
            }
            
            // If user is not admin, redirect to home page
            if ($userRole == 'Customer') {
                return redirect()->route('frontend.member.home')->with('error', 'You do not have permission to access this area.');
            }
            
            // Default redirect to home
            return redirect()->route('frontend.home')->with('error', 'Unauthorized access.');
        }
        
        // If not authenticated, redirect to login
        return redirect()->route('showLogin')->with('error', 'Please login to continue.');
    }
}

