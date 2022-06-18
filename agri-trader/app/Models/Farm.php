<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $fillable = ['farm_owner_id', 'trader_id', 'farm_hectares', 'farm_titleNum', 'farm_imageUrl', 'farm_name'];

    public function farm_owner()
    {
        return $this->belongsTo(FarmOwner::class, 'farm_owner_id');
    }
    public function trader()
    {
        return $this->belongsTo(Trader::class, 'trader_id');
    }
    public function farm_address()
    {
        return $this->hasOne(FarmAddress::class);
    }
    public function farm_partners()
    {
        return $this->belongsToMany(FarmPartner::class, 'farm_to_farm_partner_assignment', 'farm_id', 'farm_partner_id');
    }
    public function produces()
    {
        return $this->belongsToMany(Produce::class, 'farm_produce', 'farm_id', 'produce_id');
    }
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
