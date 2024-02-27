<?php
namespace App\Models;

use App\Models\LoanApplication;
use App\Models\User;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionHistory extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'transaction_histories';

    protected $dates = [
        'transaction_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'account_number_id',
        'loan_application_id',
        'audit_description',
        'transaction_type',
        'transaction_status',
        'transaction_date',
        'currently_assigned_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function account_number()
    {
        return $this->belongsTo(User::class, 'account_number_id');
    }

    public function loan_reference()
    {
        return $this->belongsTo(LoanApplication::class, 'loan_reference_id');
    }

    public function getTransactionDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setTransactionDateAttribute($value)
    {
        $this->attributes['transaction_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function currently_assigned()
    {
        return $this->belongsTo(User::class, 'currently_assigned_id');
    }
}