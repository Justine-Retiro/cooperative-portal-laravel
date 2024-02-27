<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class LoanApplication extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    public $table = 'loan_applications';

    protected $appends = [
        'applicant_sign',
        'applicant_receipt',
    ];

    protected $dates = [
        'birth_date',
        'application_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'loan_reference',
        'account_number_id',
        'customer_name',
        'age',
        'birth_date',
        'date_employed',
        'contact_num',
        'college',
        'taxid_num',
        'loan_type',
        'work_position',
        'retirement_year',
        'application_date',
        'application_status',
        'financed_amount',
        'finance_charge',
        'monthly_pay',
        'balance',
        'remarks',
        'note',
        'take_action_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function account_number()
    {
        return $this->belongsTo(User::class, 'account_number_id');
    }

    public function getBirthDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setBirthDateAttribute($value)
    {
        $this->attributes['birth_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getApplicationDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setApplicationDateAttribute($value)
    {
        $this->attributes['application_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getApplicantSignAttribute()
    {
        $file = $this->getMedia('applicant_sign')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function getApplicantReceiptAttribute()
    {
        $file = $this->getMedia('applicant_receipt')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

    public function take_action_by()
    {
        return $this->belongsTo(User::class, 'take_action_by_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'account_number_id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function mediaItems()
    {
        return $this->hasMany(Media::class, 'loan_id');
    }
    public function approvals()
    {
        return $this->hasMany(LoanApplicationApprovals::class, 'loan_id');
        // return $this->hasOne(LoanApplicationApprovals::class, 'loan_id');
    }
    public function checkapprovals()
    {
        // return $this->hasMany(LoanApplicationApprovals::class, 'loan_id');
        return $this->hasOne(LoanApplicationApprovals::class, 'loan_id');

    }
    public function transactionHistories()
    {
        return $this->hasMany(TransactionHistory::class, 'loan_application_id');
    }
}