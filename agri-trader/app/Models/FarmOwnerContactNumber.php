<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmOwnerContactNumber extends Model
{
    use HasFactory;
    protected $fillable = ['farm_owner_id', 'owner_contactNum'];

    public function farm_owner()
    {
        return $this->belongsTo(FarmOwner::class, 'farm_owner_id');
    }
}
