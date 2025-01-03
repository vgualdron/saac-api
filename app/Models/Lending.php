<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Lending extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nameDebtor',
        'address',
        'phone',
        'firstDate',
        'endDate',
        'amount',
        'amountFees',
        'percentage',
        'period',
        'order',
        'status',
        'listing_id',
        'expense_id',
        'new_id',
        'created_at',
        'type',
        'has_double_interest',
        'doubleDate',
    ];
   
    public function payments() {
       return $this->hasMany(Payment::class, 'lending_id', 'id');
    }
   
    public function reddirections() {
       return $this->hasMany(Reddirection::class, 'lending_id', 'id');
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];
}
