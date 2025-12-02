<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('frontend.showLogin')->with('error', 'Please login to continue.');
        }

        $user = auth()->user();

        // Check if user is verified
        if ($user->verification_status !== 'verified') {
            // Redirect based on verification status
            if ($user->verification_status === 'pending') {
                return redirect()->route('frontend.home')->with('warning', 'Your verification is pending admin approval. You will be notified once approved.');
            } elseif ($user->verification_status === 'rejected') {
                return redirect()->route('frontend.home')->with('error', 'Your verification was rejected. Please upload a new document to continue.');
            } else {
                // Unverified
                return redirect()->route('frontend.home')->with('warning', 'Please complete your verification to access this feature.');
            }
        }

        return $next($request);
    }
}
