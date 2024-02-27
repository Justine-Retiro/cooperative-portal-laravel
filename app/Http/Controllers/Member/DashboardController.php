<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $client = Client::where('user_id', auth()->user()->id)->first();
        $user = auth()->user();
        $balance = $client ? $client->balance : 0; // Default balance to 0 if no client is found
        $loanApplications = $client ? $client->loanApplications()->orderBy('created_at', 'desc')->get() : collect();
        $transactionHistories = $user ? $user->transactions()->orderBy('created_at', 'desc')->take(5)->get() : collect(); // Compact last 5 transaction histories
        
        return view('members.dashboard', compact('balance', 'loanApplications', 'client', 'transactionHistories'));
    }
}
