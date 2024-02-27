<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BirthdateVerificationController extends Controller
{
    public function index(){
        return view('auth.verify_birthdate');
    }
    public function verifyBirthdate(Request $request)
    {
        $user = Auth::user();
        $key = 'verify:birthdate:'.$user->id;
        $attempts = Cache::get($key, 0);
        $cooldown = Cache::get($key.':cooldown', false);

        if ($cooldown) {
            return redirect()->route('login')->withErrors(['birthdate' => 'Cooldown period. Please wait 5 minutes.']);
        }

        if ($request->birth_date == $user->birth_date) {
            Cache::forget($key);
            Cache::forget($key.':cooldown');
            Session::put('is_birthdate_verified', true);

            return redirect()->intended($this->redirectPath());
        } else {
            $attempts++;
            Cache::put($key, $attempts, 300); 

            if ($attempts >= 5) {
                Cache::put($key.':cooldown', true, 300); 
                return redirect()->route('login')->withErrors(['birthdate' => 'Too many failed attempts. Please wait 5 minutes.']);
            }

            return back()->withErrors(['birthdate' => 'Incorrect birthdate. Please try again.']);
        }
    }

    protected function redirectPath()
    {
        if (auth()->user()->role_id == 1) { 
            return route('admin.dashboard'); 
        } else if (auth()->user()->role_id == 2) { 
            return route('member.dashboard'); 
        } 
        return $this->redirectTo;
    }
}