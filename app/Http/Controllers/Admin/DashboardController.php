<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class DashboardController extends Controller
{
    public function index(){
        $totalClients = Client::count();
        $newLoanRequests = 0;
        $newLoanRequestsLast24Hours = 0;
        $pendingLoanRequests = 0; // Initialize count for pending loan requests
    
        // For users with general permission to view loan requests
        if (auth()->user()->hasPermission(1)) {
            
            // Count new loan requests that have been acted upon by a book_keeper
            $pendingLoanRequests = LoanApplication::whereHas('approvals', function($query) {
                $query->where('book_keeper', true);
            })
            ->where('application_status', 'pending')
            ->count();

            // Count new loan requests from the last 24 hours that have been acted upon by a book_keeper
            $newLoanRequestsLast24Hours = LoanApplication::whereHas('approvals', function($query) {
                                $query->where('book_keeper', true)
                                      ->where('updated_at', '>=', now()->subDay());
                            })
                            ->where('application_status', 'pending')
                            ->count();
        }
    
        // For book_keepers, count applications acted upon and pending, including those in the last 24 hours
        if (auth()->user()->hasPermission(3)) {
            $pendingLoanRequests = LoanApplication::where('application_status', 'pending')->count();
            // Count new loan requests from the last 24 hours that have been acted upon by a book_keeper
            $newLoanRequestsLast24Hours = LoanApplication::where('application_status', 'pending')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        }
        
        return view('admin.dashboard', compact('totalClients', 'newLoanRequests', 'newLoanRequestsLast24Hours', 'pendingLoanRequests'));
    }
}
