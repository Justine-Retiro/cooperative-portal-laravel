<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
    
        // Assuming the user is redirected to these routes to resolve their issues
        $allowedRoutes = [
            'password.change', // Route name for changing password
            'change.email',    // Route name for changing email
            'email.change',    // Ensure this matches the POST route for changing email
            'enter.code.email', // Add any other routes that are part of the email verification or update process
            'resend.verification.code',
            'email.verify.code',
            'email.verify.url',
        ];
    
        // Check if the current route is in the list of allowed routes
        $currentRoute = $request->route()->getName();
        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request); // Allow the request to proceed
        }
    
        // Your existing checks
        $hasDefaultPassword = $user->default_password;
        $isEmailVerified = $user->email_verified;
    
        // Redirect based on user status
        if ($hasDefaultPassword) {
            return redirect()->route('password.change');
        } elseif (!$isEmailVerified) {
            return redirect()->route('change.email');
        }
    
        return $next($request);
    }
}
