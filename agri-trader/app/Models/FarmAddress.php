<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmAddress extends Model
{
    use HasFactory;

    protected $fillable = ['farm_id', 'farm_province', 'farm_address', 'farm_zipcode', 'farm_city'];


    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
