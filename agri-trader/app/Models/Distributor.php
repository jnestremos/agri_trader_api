<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'distributor_firstName', 'distributor_lastName', 'distributor_gender', 'distributor_birthDate', 'distributor_email'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function distributor_contactNum()
    {
        return $this->hasMany(DistributorContactNumber::class, 'distributor_id');
    }
    public function distributor_address()
    {
        return $this->hasOne(DistributorAddress::class, 'distributor_id');
    }
    public function bid_order()
    {
        return $this->hasMany(BidOrder::class, 'bid_order_id');
    }
}
