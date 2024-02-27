<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LoanApplication;
use App\Models\LoanApplicationApprovals;
use App\Models\Media;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoanController extends Controller
{
    public function index(){
        $user = auth()->user();
        $client = Client::where('user_id', $user->id)->first();
        $loanApplications = $client->loanApplications()
                            ->whereIn('application_status', ['approved', 'rejected', 'pending'])
                            ->latest()
                            ->get();
        $totalLoans = $loanApplications->count();
        $totalApprovedLoans = $loanApplications->where('application_status', 'approved')->count();
        $totalRejectedLoans = $loanApplications->where('application_status', 'rejected')->count();
        $totalPendingLoans = $loanApplications->where('application_status', 'pending')->count();

        return view('members.loan.loan', compact('loanApplications', 'totalLoans', 'totalApprovedLoans', 'totalRejectedLoans', 'totalPendingLoans'));
    }
    public function add(){
        $user_id = auth()->user()->id;
        $client = User::find($user_id)->clients->first();
        return view('members.loan.add', compact('client'));
    }
    public function store(Request $request)
    {
        $clientID = User::find(auth()->user()->id)->clients->first()->id;

        $loan_reference = "4" . mt_rand(1000000, 9999999);

        $application = new LoanApplication();
        $application->loan_reference = $loan_reference;
        $application->customer_name = $request->input('customer_name');
        $application->age = $request->input('age');
        $application->birth_date = $request->input('birth_date');
        $application->date_employed = $request->input('date_employed');
        $application->contact_num = $request->input('contact');
        $application->college = $request->input('college');
        $application->taxid_num = $request->input('taxid_num');
        $application->loan_type = $request->input('loan_type');
        $application->work_position = $request->input('position');
        $application->retirement_year = $request->input('retirement_year');
        $application->application_date = $request->input('application_date');
        $application->time_pay = $request->input('time_pay');
        $application->application_status = 'pending'; // Assuming default status is pending
        $application->financed_amount = str_replace(',', '', $request->input('amount_before'));
        $application->finance_charge = $request->input('amount_after');
        $application->monthly_pay = $request->input('monthly_pay');
        $application->remarks = null;
        $application->note = null;
        $application->due_date = null;
        $application->account_number_id = auth()->user()->id; // Assuming the logged in user is the account holder
        $application->client_id = $clientID;
        $application->take_action_by_id = null; // Assuming no action has been taken yet
        $application->save();

        if ($request->hasFile('signature')) {
            $file = $request->file('signature');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Store the file in the public storage and get the path
            $customerName = str_replace(' ', '_', $request->input('customer_name'));
            $prefix = $customerName . '-' . $loan_reference . '-' . now()->format('Y_m_d_H_i_s');
            $signatureFolder = 'public/signatures/' . $prefix;
            $homepayReceiptFolder = 'public/homepay_receipts/' . $prefix;

            $signature = $file->storeAs($signatureFolder, $filename);
            $take_home_pay = $file->storeAs($homepayReceiptFolder, $filename);
            // Store the path in the media table
            $media = new Media();
            $media->signature = $signature;
            $media->take_home_pay = $take_home_pay;
            $media->loan_id = $application->id; // Assuming you have the loan application ID
            // Add any other necessary fields
            $media->save();
        }

        $client = Client::find($clientID);
        $client->increment('amount_of_share', $request->input('hidden_share'));

        $transaction = new TransactionHistory();
        $transaction->audit_description = 'Loan Application Submitted';
        $transaction->transaction_type = 'Loan Application';
        $transaction->transaction_status = 'Pending';
        $transaction->transaction_date = now()->setTimezone('Asia/Manila');
        $transaction->account_number_id = auth()->user()->id;
        $transaction->loan_application_id = $application->id;
        $transaction->currently_assigned_id = null;
        $transaction->save();

        $response = new LoanApplicationApprovals;
        $response->loan_id = $application->id;
        $response->book_keeper = false;
        $response->general_manager = false;
        $response->save();

        return redirect()->route('member.loan')->with('success', 'Loan application submitted successfully.');
    }
}
