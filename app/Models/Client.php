<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'clients';

    protected $dates = [
        'birth_date',
        'date_employed',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'citizenship',
        'civil_status',
        'spouse_name',
        'provincial_address',
        'city_address',
        'mailing_address',
        'birth_date',
        'birth_place',
        'phone_number',
        'tax_id_number',
        'date_employed',
        'position',
        'nature_of_work',
        'account_status',
        'amount_of_share',
        'balance',
        'remarks',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_id',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function loanApplications(){
        return $this->hasMany(LoanApplication::class, 'client_id');
    }
    public function loans(){
        return $this->hasMany(LoanApplication::class, 'client_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(TransactionHistory::class);
    }
}