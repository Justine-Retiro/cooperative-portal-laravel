<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUser
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
        if (!Auth::check() || !session('is_birthdate_verified') || Auth::user()->role_id == null) {
            return redirect()->route('login');
        }
        // Get the user's role
        $userRole = Auth::user()->role_id;
        if ($userRole == 2) {
            return $next($request);
        }    
        return $next($request);
    }
}
