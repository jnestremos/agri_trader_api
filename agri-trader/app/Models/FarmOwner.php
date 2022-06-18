<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmOwner extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'owner_firstName', 'owner_lastName', 'owner_gender', 'owner_birthDate', 'owner_email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function farm()
    {
        return $this->hasMany(Farm::class, 'farm_owner_id');
    }
    public function farm_owner_contactNum()
    {
        return $this->hasMany(FarmOwnerContactNumber::class, 'farm_owner_id');
    }
    public function farm_owner_address()
    {
        return $this->hasOne(FarmOwnerAddress::class);
    }
    public function traders()
    {
        return $this->belongsToMany(Trader::class, 'owner_trader', 'farm_owner_id', 'trader_id');
    }
}
