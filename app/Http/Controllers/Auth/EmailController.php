<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index(){
        return view('auth.email_change');
    }

    public function entercode(){
        return view('auth.emailcode_verification');
    }

    public function change(Request $request){
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
        ]);
    
        $user = Auth::user();
        $user->email = $request->email;
        // Generate a simple random verification code and encode it in hexadecimal
        $user->code = strtoupper(substr(dechex(rand(100000, 999999)) . bin2hex(random_bytes(3)), 0, 6));
        
        if (!$user->save()) {
            throw new \Exception('Failed to save user details.');
        }
        Log::info('Request received', $request->all());

        // Attempt to send verification email
        try {
            Mail::to($user->email)->send(new VerifyEmail($user));
        } catch (\Exception $e) {
            throw new \Exception('Failed to send verification email.');
        }
    
        return redirect()->route('enter.code.email')->with('success', 'Verification email sent successfully!');
    }

    public function resendVerificationCode()
    {
        try {
            $user = Auth::user();
            $user->code = strtoupper(substr(dechex(rand(100000, 999999)) . bin2hex(random_bytes(3)), 0, 6));
            $user->save();
    
            Mail::to($user->email)->send(new VerifyEmail($user));
    
            return response()->json(['message' => 'Verification code resent successfully.']);
        } catch (\Exception $e) {
            Log::error('Failed to resend verification code: ' . $e->getMessage());
            return response()->json(['message' => 'Error resending the code. Please try again later.'], 500);
        }
    }

    
    public function verify(Request $request, $code = null)
    {
        $loggedInUser = Auth::user();
        $inputCode = strtoupper(trim($code ?? $request->input('code')));

        $user = User::where('code', $inputCode)->first();

        if ($user && $loggedInUser && $loggedInUser->id === $user->id) {

            $user->code = null;
            $user->email_verified = true;
            $user->email_verified_at = now();
            $user->save();
            session(['is_birthdate_verified' => true]);
            \Log::info('Redirecting to: ' . $this->redirectPath());
            return redirect()->intended($this->redirectPath());
        } else {

    
            return back()->with('error', 'Invalid verification code or user mismatch.');
        }
    }
    
    protected function redirectPath()
    {
        if (Auth::user()->role_id == 1) { 
            return route('admin.dashboard'); 
        } else if (Auth::user()->role_id == 2) { 
            return route('member.dashboard'); 
        } 
        return $this->redirectTo;
    }

}

