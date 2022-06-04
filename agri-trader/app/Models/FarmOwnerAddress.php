<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmOwnerAddress extends Model
{
    use HasFactory;
    protected $fillable = ['farm_owner_id', 'owner_province', 'owner_address', 'owner_zipcode'];

    public function farm_owner()
    {
        return $this->belongsTo(FarmOwner::class);
    }
}
