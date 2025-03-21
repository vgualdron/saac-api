<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $connection = 'mysql';
    public $table = "users";

    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_document',
        'document_number',
        'name',
        'phone',
        'password',
        'editable',
        'active',
        'push_token',
        'latitude',
        'longitude',
        'date_location',
        'created_at',
        'updated_at',
        'changePaswword',
        'changePhoto',
        'completedFields',
        'payment_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    public function findForPassport($username){
        return $user = (new User)->where('document_number', $username)->first();
    }
}
