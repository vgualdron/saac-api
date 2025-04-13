<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $connection = 'mysql';
    public $table = "credits";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'credit_date',
        'approved_date',
        'associated_id',
        'creditline_id',
        'annual_interest',
        'credit_interest',
        'debtor_insurance',
        'credit_insurance',
        'credit_term',
        'credit_value',
        'quota_value',
        'request_observation',
        'status',
        'created_by',
        'otp',
        'user_id',
    ];
}
