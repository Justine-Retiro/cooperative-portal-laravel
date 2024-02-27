<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LoanApplication;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(){
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();
    
        // Assuming the relationship is named 'loanApplications'
        $loanApplications = $client->loanApplications()
                                    ->whereIn('application_status', ['approved', 'rejected', 'pending'])
                                    ->latest()
                                    ->get();
    
        $balance = $client->balance ? $client->balance : '0.00';
        
        // Check if there is a recent loan application and it's within the last 30 seconds
        $loanApplicationStatus = 'No recent application';
        if ($loanApplications->isNotEmpty()) {
            $recentApplication = $loanApplications->first();
            if ($recentApplication->application_status === 'pending') {
                $loanApplicationStatus = 'pending';
            } else {
                $timeSinceUpdate = now()->diffInSeconds($recentApplication->updated_at);
                
                if (in_array($recentApplication->application_status, ['approved', 'rejected'])) {
                    if ($timeSinceUpdate <= 2592000) { 
                        $loanApplicationStatus = $recentApplication->application_status;
                    }
                }
            }
        }
        $recentApplicationUpdateTimestamp = $loanApplications->isNotEmpty() ? $loanApplications->first()->updated_at : null;

        // Fetch payments related to the client
        $payments = $user->payments()->with(['payment_pivot.loanApplication'])->orderBy('created_at', 'desc')->get();
        
        return view('members.account', [
            'loanBalance' => $balance,
            'remarks' => $client->remarks,
            'amount_of_shares' => $client->amount_of_share,
            'loanApplicationStatus' => $loanApplicationStatus,
            'payments' => $payments,
            'loanApplications' => $loanApplications, 
            'recentApplicationUpdateTimestamp' => $recentApplicationUpdateTimestamp,
        ]);
    }
}
