<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LoanApplication;
use App\Models\LoanApplicationApprovals;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoanProcessing extends Controller
{
    public function index(){
        $user = auth()->user();
    
        // Initialize variables to store counts
        $allCount = 0;
        $pendingCount = 0;
        $approvedCount = 0;
        $rejectedCount = 0;
    
        if ($user->hasPermission(1)) {
            // For users with permission level 1
            $loanApplications = LoanApplication::whereHas('approvals', function ($query) {
                $query->where('book_keeper', true);
            })->where('application_status', '!=', 'reject')->get();
    
            // Calculate counts based on the same conditions
            $allCount = $loanApplications->count();
            $pendingCount = $loanApplications->where('application_status', 'pending')->count();
            $approvedCount = $loanApplications->where('application_status', 'approved')->count();
            $rejectedCount = $loanApplications->where('application_status', 'rejected')->count();
        } elseif ($user->hasPermission(3)) {
            // For users with permission level 3
            $overAllloanApplications = LoanApplication::whereHas('approvals', function ($query) {
                $query->where('book_keeper', true)
                    ->where('general_manager', true);
            })->get();
            $loanApplications = LoanApplication::whereHas('approvals', function ($query) {
                $query->where('book_keeper', false);
            })->where('application_status', '!=', 'reject')->get();
            
            // Calculate counts
            $pendingCount = $loanApplications->where('application_status', 'pending')->count();
            $allCount = $overAllloanApplications->count() - $pendingCount;
            $approvedCount = $overAllloanApplications->where('application_status', 'approved')->count();
            $rejectedCount = $overAllloanApplications->where('application_status', 'rejected')->count();
        } else {
            // For other users
            $loanApplications = LoanApplication::where('application_status', 'pending')->get();
    
            // Since other users only see pending applications, set counts accordingly
            $allCount = $loanApplications->count();
            $pendingCount = $allCount;
            // Approved and rejected counts will remain 0 for users who can only see pending applications
        }
    
        return view('admin.loans.loan', compact('loanApplications', 'allCount', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    public function filterLoansByStatus(Request $request, $status)
    {
        $user = auth()->user();
        $query = LoanApplication::query();
        $searchQuery = $request->query('search', '');
        $sort = $request->query('sort', 'desc'); // Capture the sort option
        
        // Apply initial permission and status filters
        if ($user->hasPermission(1)) {
            $query->whereHas('approvals', function ($query) use ($status) {
                $query->where('book_keeper', true);
            });
            if ($status !== 'all') {
                $query->where('application_status', $status);
            } else {
                $query->where('application_status', '!=', 'reject');
            }
        } elseif ($user->hasPermission(3)) {
            // $query->whereHas('approvals', function ($query) use ($status) {
            //     $query->where('book_keeper', true);
            // });
            if ($status !== 'all' && $status !== 'pending') {
                $query->where('application_status', $status);
            } else if ($status === 'all') {
            $query->whereHas('approvals', function ($query) {
                $query->where('book_keeper', true)
                      ->where('general_manager', true);
            });
            } else if ($status === 'pending') {
                // Removed unnecessary fetching of loan applications
                // Now directly filtering the query for 'pending' status for permission level 3 users
                $query->whereHas('approvals', function ($query) {
                    $query->where('book_keeper', false);
                })->where('application_status', 'pending');
            }
        } else {
            if ($status === 'pending' || $status === 'all') {
                $query->where('application_status', 'pending');
            } else {
                // No results for unauthorized status
                $query->where('id', '=', 0);
            }
        }
        
        // Apply search query within the permission and status logic
        if (!empty($searchQuery)) {
            $query->where(function($q) use ($searchQuery) {
                $q->whereHas('user', function ($subQuery) use ($searchQuery) {
                    $subQuery->where('account_number', 'like', "%{$searchQuery}%");
                })->orWhere('customer_name', 'like', "%{$searchQuery}%");
            });
        }
        
        // Apply sorting
        $query->orderBy('created_at', $sort);
        
        // Correctly apply pagination
        $loanApplications = $query->paginate(20);
        
        // For JSON response, including pagination links
        return response()->json([
            'html' => view('admin.loans.partials.loan_table', compact('loanApplications'))->render(),
            'pagination' => $loanApplications->appends([
                'sort' => $sort,
                'search' => $searchQuery,
                'status' => $status
            ])->links()->toHtml(),
            'counts' => [
                'all' => LoanApplication::count(),
                'pending' => LoanApplication::where('application_status', 'pending')->count(),
                'approved' => LoanApplication::where('application_status', 'approved')->count(),
                'rejected' => LoanApplication::where('application_status', 'rejected')->count(),
            ],
                
        ]);
    }

    public function application(Request $request, $loanReference)
    {
        $currentLoanApplication = LoanApplication::with('approvals')->where('loan_reference', $loanReference)->firstOrFail();

        $bookKeeperApproved = $currentLoanApplication->approvals->contains(function($approval) {
            return $approval->book_keeper == true;
        }) && $currentLoanApplication->application_status !== 'reject';

        // Retrieve sort and direction parameters from the request, with defaults
        $sortColumn = $request->query('sort', 'application_date'); // Default sort column
        $sortDirection = $request->query('direction', 'desc'); // Default sort direction changed to 'desc'

        // Initialize the query
        $query = LoanApplication::where('account_number_id', $currentLoanApplication->account_number_id);

        if ($sortColumn == 'application_status') {
            // Handle custom sorting for application_status
            switch ($sortDirection) {
                case 'pending':
                    $query->orderByRaw("FIELD(application_status, 'pending', 'approved', 'rejected')");
                    break;
                case 'approved':
                    $query->orderByRaw("FIELD(application_status, 'approved', 'rejected', 'pending')");
                    break;
                case 'rejected':
                    $query->orderByRaw("FIELD(application_status, 'rejected', 'pending', 'approved')");
                    break;
                default:
                    // Fallback to a default sort if an unexpected direction is provided
                    $query->orderBy('application_status', 'asc');
                    break;
            }
        } else {
            // For other columns, ensure the direction is either 'asc' or 'desc'
            $direction = in_array(strtolower($sortDirection), ['asc', 'desc']) ? $sortDirection : 'desc'; // Ensure default direction is 'desc' if not specified
            $query->orderBy($sortColumn, $direction);
        }

        // Execute the query with pagination
        $pastLoanApplications = $query->paginate(5);

        $mediaItems = $currentLoanApplication->mediaItems()->where('loan_id', $currentLoanApplication->id)->get();

        if ($request->ajax()) {
            $tbodyHtml = view('admin.loans.partials.loan_table_info', ['pastLoanApplications' => $pastLoanApplications])->render();
            return response()->json([
                'table' => $tbodyHtml, // Now this contains only <tbody> HTML
            ]);
        }

        return view('admin.loans.application', compact('currentLoanApplication', 'pastLoanApplications', 'mediaItems', 'bookKeeperApproved'));
    }


    public function approveByLevel3(Request $request, $loanReference)
    {
        $loanApplication = LoanApplication::where('loan_reference', $loanReference)->firstOrFail();
        $user = auth()->user();

        if ($user->hasPermission(3)) {
            \Log::info('Note:', ['note' => $request->input('note')]);
            $loanApplication->update(['note' => $request->input('note')]);
            // $loanApplication->update(['application_status' => '']);
            $transaction = new TransactionHistory();
            $transaction->audit_description = 'Loan Application Response';
            $transaction->transaction_type = 'Loan Application';
            $transaction->transaction_status = 'Accepted';
            $transaction->transaction_date = now()->setTimezone('Asia/Manila');
            $transaction->account_number_id = $loanApplication->account_number_id;
            $transaction->loan_application_id = $loanApplication->id;
            $transaction->currently_assigned_id = auth()->id();
            $transaction->save();
            LoanApplicationApprovals::where('loan_id', $loanApplication->id)->update(['book_keeper' => true]);
            return redirect()->back()->with('success', 'Application approved successfully.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
    }

    public function rejectByLevel3(Request $request, $loanReference)
    {
        $loanApplication = LoanApplication::where('loan_reference', $loanReference)->firstOrFail();
        $user = auth()->user();

        if ($user->hasPermission(3)) {
            $loanApplication->update(['note' => $request->input('note')]);
            $loanApplication->update(['application_status' => 'rejected']);
            
            $transaction = new TransactionHistory();
            $transaction->audit_description = 'Loan Application Response';
            $transaction->transaction_type = 'Loan Application';
            $transaction->transaction_status = 'Rejected';
            $transaction->transaction_date = now()->setTimezone('Asia/Manila');
            $transaction->account_number_id = $loanApplication->account_number_id;
            $transaction->loan_application_id = $loanApplication->id;
            $transaction->currently_assigned_id = auth()->id();
            $transaction->save();
            LoanApplicationApprovals::where('loan_id', $loanApplication->id)->update(['book_keeper' => true]);
            return redirect()->back()->with('success', 'Application rejected successfully.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
    }

    public function finalAcceptance(Request $request, $loanReference)
    {
        $loanApplication = LoanApplication::where('loan_reference', $loanReference)->firstOrFail();
        $client = Client::where('id', $loanApplication->client_id)->first();

        $user = auth()->user();

        if ($user->hasPermission(1)) {
            $loanApplication->update(['application_status' => 'approved']);
            $loanApplication->update(['remarks' => 'unpaid']);
            $loanApplication->update(['due_date' => $request->input('due_date')]);
            $loanApplication->update(['note' => $request->input('note')]);
            $loanApplication->update(['balance' => $loanApplication->finance_charge]);

            $transaction = new TransactionHistory();
            $transaction->audit_description = 'Loan Application Response';
            $transaction->transaction_type = 'Loan Application';
            $transaction->transaction_status = 'Accepted';
            $transaction->transaction_date = now()->setTimezone('Asia/Manila');
            $transaction->account_number_id = $loanApplication->account_number_id;
            $transaction->loan_application_id = $loanApplication->id;
            $transaction->currently_assigned_id = auth()->id();
            $transaction->save();
            if ($client) { // Check if a client was found
                $client->balance = $client->balance + $loanApplication->financed_amount;
                $client->update(['remarks' => 'unpaid']);
                $client->save();
            }
            LoanApplicationApprovals::where('loan_id', $loanApplication->id)->update(['general_manager' => true]);
            return redirect()->back()->with('success', 'Final acceptance successful.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized action or invalid application status.');
        }
    }

    public function rejectByLevel1(Request $request, $loanReference)
    {
        $action = new LoanApplicationApprovals;
        $loanApplication = LoanApplication::where('loan_reference', $loanReference)->firstOrFail();
        $user = auth()->user();

        if ($user->hasPermission(1)) {
            $loanApplication->update(['note' => $request->input('note')]);
            $loanApplication->update(['application_status' => 'rejected']);
            $transaction = new TransactionHistory();
            $transaction->audit_description = 'Loan Application Response';
            $transaction->transaction_type = 'Loan Application';
            $transaction->transaction_status = 'Rejected';
            $transaction->transaction_date = now()->setTimezone('Asia/Manila');
            $transaction->account_number_id = $loanApplication->account_number_id;
            $transaction->loan_application_id = $loanApplication->id;
            $transaction->currently_assigned_id = auth()->id();
            $transaction->save();
            LoanApplicationApprovals::where('loan_id', $loanApplication->id)->update(['general_manager' => true]);
            return redirect()->back()->with('success', 'Application rejected successfully.');
        } else {
            return redirect()->back()->with('error', 'Unauthorized action or invalid application status.');
        }
    }

    
}
