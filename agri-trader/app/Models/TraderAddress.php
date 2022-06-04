<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderAddress extends Model
{
    use HasFactory;
    //protected $table = 'trader_addresses';
    protected $fillable = ['trader_id', 'trader_province', 'trader_address', 'trader_zipcode'];

    public function trader()
    {
        return $this->belongsTo(Trader::class, 'trader_id');
    }
}
