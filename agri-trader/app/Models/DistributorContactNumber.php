<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributorContactNumber extends Model
{
    use HasFactory;
    protected $fillable = ['distributor_id', 'distributor_contactNum'];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
}
