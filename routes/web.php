<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanProcessing;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RepositoriesController;
use App\Http\Controllers\Auth\BirthDateVerificationController;
use App\Http\Controllers\Auth\EmailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Member\AccountController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\LoanController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', [LoginController::class, 'showLogin' ])->name('login');
Route::get('/reset-password', [ForgotPasswordController::class, 'index'])->name('forgot.password');
Route::post('login', [LoginController::class, 'login'])->name('login.user');

Route::get('/view', function () {
    return view('emails.verification');
})->name('view');

Route::group(['middleware' => ['auth.verification']], function () {
    Route::get('/verify-birthdate', [BirthDateVerificationController::class, 'index'])->name('verify.birthdate');
    Route::post('/verify-birthdate', [BirthDateVerificationController::class, 'verifyBirthdate'])->name('birthdate.verify');
});
Route::group(['middleware' => ['auth', 'newUser']], function () {
    Route::get('/change-password', [PasswordController::class, 'index'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'change'])->name('change.password');
    
    // Email view
    Route::get('/change-email', [EmailController::class, 'index'])->name('change.email');
    Route::post('/change-email', [EmailController::class, 'change'])->name('email.change');
    // Verify Email through code
    Route::get('/change-email/verification', [EmailController::class, 'entercode'])->name('enter.code.email');
    // Resend Code
    Route::post('/change-email/resend-verification-code', [EmailController::class, 'resendVerificationCode'])->name('resend.verification.code');
    // Code Based
    Route::post('/change-email/verify-email', [EmailController::class, 'verify'])->name('email.verify.code');
    // Url Based
    Route::get('/change-email/verify-email/{code}', [EmailController::class, 'verify'])->name('email.verify.url');
});

// Route::get('/documents/{path}', 'DocumentController@show')->where('path', '.*')->middleware('auth');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'checkUser']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Group repository routes together and apply the CheckPermission middleware
    Route::group(['middleware' => ['check.permission:1,3']], function () {
        // Permission 1 & 3
        // Repositories 
        Route::get('/repositories', [RepositoriesController::class, 'index'])->name('admin.repositories');
        Route::get('/repositories/add', [RepositoriesController::class, 'add'])->name('admin.add-repo');
        Route::get('/repositories/edit/{id}', [RepositoriesController::class, 'edit'])->name('admin.edit-repo');
        // Post HTTP Request form for new member
        Route::post('/repositories/add-repo', [RepositoriesController::class, 'store'])->name('admin.store-repo');
        // Edit HTTP Request form for editing member
        Route::put('/repositories/edit/{id}/update', [RepositoriesController::class, 'update'])->name('admin.update-repo');
        // Delete HTTP Request 
        Route::delete('/repositories/delete/{id}', [RepositoriesController::class, 'edit'])->name('admin.delete-repo');
        // Repository search
        Route::get('/repositories/search', [RepositoriesController::class, 'search'])->name('admin.search-repo');

        // Memeber's Loan
        Route::get('/loan', [LoanProcessing::class, 'index'])->name('admin.loan.home');
        Route::get('/loan/application/{loanReference}', [LoanProcessing::class, 'application'])->name('admin.loan.application');
        // Route for Book keeper approval
        // ------------------------------
        // Approval
        Route::post('/loan/approveByLevel3/{loanReference}', [LoanProcessing::class, 'approveByLevel3'])->name('admin.loan.approveByLevel3');
        // Reject
        Route::post('/loan/rejectByLevel3/{loanReference}', [LoanProcessing::class, 'rejectByLevel3'])->name('admin.loan.rejectByLevel3');
        // ------------------------------
        // Route for General manager [ Final Decision ]
        // ------------------------------
        // Approval
        Route::post('/finalAcceptance/{loanReference}', [LoanProcessing::class, 'finalAcceptance'])->name('admin.loan.finalAcceptance');
        // Reject
        Route::post('/rejectByLevel1/{loanReference}', [LoanProcessing::class, 'rejectByLevel1'])->name('admin.loan.rejectByLevel1');

        // Sort
        Route::get('/loans/filter/{status}', [LoanProcessing::class, 'filterLoansByStatus'])->name('admin.loans.filter');
        Route::get('/loans/search', [LoanController::class, 'search'])->name('admin.loans.search');
        // End Member's Loan
        // End of permission 1 & 3
    });

    // Profile Route
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    // Change Profile Details
    Route::put('/profile', [AdminProfileController::class, 'store'])->name('admin.profile.store');
    // Change Email
    // Validate email
    Route::post('/profile/email', [AdminProfileController::class, 'email_change'])->name('admin.email.change');
    // Resend Code
    Route::post('/profile/email/resend', [AdminProfileController::class, 'resendVerificationCode'])->name('admin.resend.code');
    // Verify code
    Route::post('/profile', [AdminProfileController::class, 'verify'])->name('admin.email.verify');
    // Profile Route End

    // Change Password
    // Validate
    Route::post('/profile/password', [AdminProfileController::class, 'validate_password'])->name('admin.password.validate');
    // Resend Code
    Route::post('/profile/password/resend', [AdminProfileController::class, 'resend_password_code'])->name('admin.password.resend');
    // Verify Code
    Route::post('/profile/password/verify', [AdminProfileController::class, 'verify_password_code'])->name('admin.password.verify');

    Route::group(['middleware' => ['check.permission:2']], function () {
        // Payment
        Route::get('/payment', [PaymentController::class, 'index'])->name('admin.payment');
        Route::get('/payment/edit/{user_id}', [PaymentController::class, 'edit'])->name('admin.payment.edit');
        Route::get('/payment/edit/{user_id}/{loanNo}', [PaymentController::class, 'getLoanDetails'])->name('admin.payment.getLoanDetails');
        Route::post('/payment/edit/store', [PaymentController::class, 'storePayment'])->name('admin.payment.store');
    });
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'member', 'middleware' => ['auth', 'checkUser']], function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    Route::get('/account', [AccountController::class, 'index'])->name('member.account');

    // Loan Routes
    Route::get('/loan', [LoanController::class, 'index'])->name('member.loan');
    Route::get('/loan/apply', [LoanController::class, 'add'])->name('member.loan.apply');
    // Store Request for loan application
    Route::post('/loan/apply/request', [LoanController::class, 'store'])->name('member.loan.request'); 
    // End of loan routes

    // Profile Route
    Route::get('/profile', [ProfileController::class, 'index'])->name('member.profile');
    // Change Profile Details
    Route::put('/profile', [ProfileController::class, 'store'])->name('member.profile.store');
    // Change Email
    // Validate email
    Route::post('/profile/email', [ProfileController::class, 'email_change'])->name('member.email.change');
    // Resend Code
    Route::post('/profile/email/resend', [ProfileController::class, 'resendVerificationCode'])->name('member.resend.code');
    // Verify code
    Route::post('/profile', [ProfileController::class, 'verify'])->name('member.email.verify');
    // Profile Route End

    // Change Password
    // Validate
    Route::post('/profile/password', [ProfileController::class, 'validate_password'])->name('member.password.validate');
    // Resend Code
    Route::post('/profile/password/resend', [ProfileController::class, 'resend_password_code'])->name('member.password.resend');
    // Verify Code
    Route::post('/profile/password/verify', [ProfileController::class, 'verify_password_code'])->name('member.password.verify');
});