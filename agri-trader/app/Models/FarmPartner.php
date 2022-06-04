<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmPartner extends Model
{
    use HasFactory;
    protected $fillable = ['trader_id', 'partner_firstName', 'partner_lastName', 'partner_birthDate', 'partner_status'];

    public function partner_address()
    {
        return $this->hasOne(FarmPartnerAddress::class);
    }
    public function partner_contactNum()
    {
        return $this->hasMany(FarmPartnerContactNumber::class);
    }
    public function farms()
    {
        return $this->belongsToMany(Farm::class, 'farm_to_farm_partner_assignment', 'farm_partner_id', 'farm_id');
    }
}
