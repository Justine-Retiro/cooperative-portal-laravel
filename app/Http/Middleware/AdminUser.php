<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUser
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
        if (!Auth::check() || Auth::user()->role_id == null || !session('is_birthdate_verified')) {
            return redirect()->route('login');
        }
        if (Auth::user()->role_id == 2) {
            return $next($request);
        }
        if (Auth::user()->role_id != 1) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
