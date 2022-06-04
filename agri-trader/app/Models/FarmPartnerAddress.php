<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmPartnerAddress extends Model
{
    use HasFactory;
    protected $fillable = ['farm_partner_id', 'partner_province', 'partner_address', 'partner_zipcode'];

    public function farm_partner()
    {
        return $this->belongsTo(FarmPartner::class);
    }
}
