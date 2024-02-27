<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicationApprovals extends Model
{
    use HasFactory;

    protected $table = 'loan_application_approvals';

    protected $fillable = [
        'loan_id',
        'book_keeper',
        'general_manager',
    ];
}
