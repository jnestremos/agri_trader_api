<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorAddress extends Model
{
    use HasFactory;
    protected $fillable = ['distributor_id', 'distributor_province', 'distributor_address', 'distributor_zipcode'];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
}
