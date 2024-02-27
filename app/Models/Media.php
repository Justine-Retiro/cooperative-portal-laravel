<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    public $table = 'media';

    protected $fillable = [
        'signature',
        'take_home_pay',
        'loan_id',
    ];


}
