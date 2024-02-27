<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /**
     * Handle login requests.
     *
     * @param Request $request
     * @throws ValidationException
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */

    protected $redirectTo = '/';


    public function showLogin(){
        return view('auth.login');
    }

    protected function isDefaultPassword($user)
    {
        return $user->default_password;
    }
    protected function isEmailVerified($user)
    {
        return $user->email_verified;
    }
    protected function isFullyVerified($user)
    {
        return $user->email_verified && !$this->isDefaultPassword($user);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'account_number' => ['required', 'integer'],
            'password' => ['required'],
        ]);

        $user = Auth::getProvider()->retrieveByCredentials($request->only('account_number'));

        if (!$user) {
            return redirect()->back()->withErrors(['account_number' => 'The account number does not exist.'])->withInput($request->only('account_number'));
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if the user has a default password
            $hasDefaultPassword = $this->isDefaultPassword($user);
            // Check if the user's email is verified
            $isEmailVerified = $this->isEmailVerified($user);
            // Determine if the user is fully verified
            $isFullyVerified = $this->isFullyVerified($user);

            // Redirect based on user status
            if ($hasDefaultPassword) {
                return redirect()->route('password.change');
            } elseif (!$isEmailVerified) {
                return redirect()->route('change.email');
            } elseif (!$isFullyVerified) {
                return redirect()->route('password.change');
            } 

            // If the user is fully verified, redirect to the dashboard or a post-login page
            return redirect()->route('verify.birthdate');
        }

        return redirect()->back()->withErrors(['password' => 'The provided password is incorrect.'])->withInput($request->only('account_number'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
