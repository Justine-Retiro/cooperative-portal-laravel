<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'payments';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'reference_no',
        'client_id',
        'account_number_id',
        'loan_reference_id',
        'amount_paid',
        'current_balance',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function loan_references()
    {
        return $this->belongsToMany(LoanApplication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'account_number_id');
    }
    public function payment_pivot()
    {
        return $this->hasMany(LoanApplicationPayment::class, 'payment_id');
    }
    public function transactionHistories()
    {
        // Assuming the relationship is correctly defined in the LoanApplication model now
        return $this->loan_references()->with('transactionHistories')->get()->pluck('transactionHistories')->collapse();
    }
}