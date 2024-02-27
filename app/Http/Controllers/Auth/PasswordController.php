<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function index()
    {
        return view('auth.password_change');
    }

    public function change(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:8'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->default_password = false;
        $user->save();

        // Redirect to a changing email page
        return redirect()->route('change.email');
    }
}
