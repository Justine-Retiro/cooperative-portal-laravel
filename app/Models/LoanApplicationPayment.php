<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplicationPayment extends Model
{
    use HasFactory;
    
    public $table = 'loan_application_payment';
    protected $fillable = [
        'id', 
        'payment_id', 
        'loan_application_id', 
        'remarks'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }
}
