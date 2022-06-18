<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trader extends Model
{
    use HasFactory;
    //protected $table = 'traders';
    protected $fillable = ['user_id', 'trader_firstName', 'trader_lastName', 'trader_gender', 'trader_birthDate', 'trader_email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function farm()
    {
        return $this->hasMany(Farm::class, 'trader_id');
    }
    public function trader_contactNum()
    {
        return $this->hasMany(TraderContactNumber::class, 'trader_id');
    }
    public function trader_address()
    {
        return $this->hasOne(TraderAddress::class, 'trader_id');
    }
    public function produces()
    {
        return $this->belongsToMany(Produce::class, 'produce_trader', 'trader_id', 'produce_id');
    }
    public function farm_owners()
    {
        return $this->belongsToMany(FarmOwner::class, 'owner_trader', 'trader_id', 'farm_owner_id');
    }
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
    public function bid_order()
    {
        return $this->hasMany(BidOrder::class, 'bid_order_id');
    }
}
