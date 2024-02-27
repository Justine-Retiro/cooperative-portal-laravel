<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Mail\VerifyEmailProfile;
use App\Mail\VerifyPassword;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(){
        $user = auth()->user();
        $client = $user->clients()->first();
        $email = $user->email;
        return view('members.profile', compact('client'));
    }
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $client = $user->clients()->first();

            // Update client information with request data, only if present in the request
            $client->last_name = $request->input('last_name', $client->last_name);
            $client->middle_name = $request->input('middle_name', $client->middle_name);
            $client->first_name = $request->input('first_name', $client->first_name);
            $client->city_address = $request->input('city_address', $client->city_address);
            $client->phone_number = $request->input('phone_num', $client->phone_number);
            $client->position = $request->input('position', $client->position);
            $client->civil_status = $request->input('civil_status', $client->civil_status);
            $client->spouse_name = $request->input('spouse_name', $client->spouse_name);
            $client->provincial_address = $request->input('provincial_address', $client->provincial_address);
            $client->citizenship = $request->input('citizenship', $client->citizenship);
            $client->birth_date = $request->input('birth_date', $client->birth_date);
            $user->birth_date = $request->input('birth_date', $user->birth_date);
            $client->birth_place = $request->input('birth_place', $client->birth_place);
            $client->tax_id_number = $request->input('taxID_num', $client->tax_id_number);
            $client->mailing_address = $request->input('mailing_address', $client->mailing_address);
            $client->nature_of_work = $request->input('natureOf_work', $client->nature_of_work);

            // Save the updated client information
            $client->save();
            $user->save();
            // Redirect back or to another page with a success message
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }
    public function email_change(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'current_password' => ['required'], // Ensure the current password is provided
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            // Return JSON response for AJAX call
            return response()->json(['success' => false, 'message' => 'The provided password does not match your current password.'], 200);
        }

        // Store the new email in a different column until it's verified
        $user->new_email = $request->email;
        // Generate a simple random verification code and encode it in hexadecimal
        $user->code = strtoupper(substr(dechex(rand(100000, 999999)) . bin2hex(random_bytes(3)), 0, 6));
        
        if (!$user->save()) {
            throw new \Exception('Failed to save user details.');
        }

        try {
            // Send verification email to the new email address
            Mail::to($user->new_email)->send(new VerifyEmailProfile($user));
        } catch (\Exception $e) {
            throw new \Exception('Failed to send verification email.');
        }

        return response()->json(['status' => 'success', 'message' => 'Verification email sent successfully to your new email.']);
    }
    public function resendVerificationCode()
    {
        try {
            $user = Auth::user();
            $user->code = strtoupper(substr(dechex(rand(100000, 999999)) . bin2hex(random_bytes(3)), 0, 6));
            $user->save();
    
            Mail::to($user->new_email)->send(new VerifyEmailProfile($user));
    
            return response()->json(['message' => 'Verification code resent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error resending the code. Please try again later.']);
        }
    }
    public function verify(Request $request){
        $request->validate([
            'code' => ['required'],
        ]);

        $user = Auth::user();
        $code = $request->code;
        if ($code == $user->code){
            try {
                $user->email = $user->new_email;
                $user->new_email = null; // This line is simplified as it's redundant to check if email == new_email before setting new_email to null
                $user->code = null;

                $user->save(); // Save the user model after making changes

                return response()->json(['success' => true, 'message' => 'Email verification successful.']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'The verification code entered is incorrect. Please try again.']);
        }   
    }

    public function validate_password(Request $request){
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required'],
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            // Return JSON response for AJAX call
            return response()->json(['success' => false, 'message' => 'The provided password does not match your current password.']);
        }

        $user->code = strtoupper(substr(dechex(rand(100000, 999999)) . bin2hex(random_bytes(3)), 0, 6));
        $user->new_password = Hash::make($request->new_password);        

        if (!$user->save()) {
            throw new \Exception('Failed to save user details.');
        }

        try {
            // Send verification email to the new email address
            Mail::to($user->email)->send(new VerifyPassword($user));
        } catch (\Exception $e) {
            throw new \Exception('Failed to send verification email.');
        }

        return response()->json(['status' => 'success', 'message' => 'Verification code sent successfully to your email.']);

    }
    public function resend_password_code(){
        try {
            $user = Auth::user();
            $user->code = strtoupper(substr(dechex(rand(100000, 999999)) . bin2hex(random_bytes(3)), 0, 6));
            $user->save();
    
            Mail::to($user->email)->send(new VerifyPassword($user));
    
            return response()->json(['message' => 'Verification code resent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error resending the code. Please try again later.']);
        }
    }
    public function verify_password_code(Request $request){
        $request->validate([
            'code' => ['required'],
        ]);

        $user = Auth::user();
        $code = $request->code;
        if ($code == $user->code){
            try {
                $user->password = $user->new_password;
                $user->new_password = null; // This line is simplified as it's redundant to check if email == new_email before setting new_email to null
                $user->code = null;

                $user->save(); // Save the user model after making changes

                return response()->json(['success' => true, 'message' => 'Code verification successful.']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'The verification code entered is incorrect. Please try again.']);
        }   
    }
}
