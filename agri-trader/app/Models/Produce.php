<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produce extends Model
{
    use HasFactory;
    protected $fillable = ['trader_id', 'prod_name', 'prod_timeOfHarvest', 'prod_type'];

    public function traders()
    {
        return $this->belongsToMany(Trader::class, 'produce_trader', 'produce_id', 'trader_id');
    }
    public function farms()
    {
        return $this->belongsToMany(Farm::class, 'farm_produce', 'produce_id', 'farm_id');
    }
    public function contract()
    {
        return $this->hasMany(Contract::class);
    }
    public function produce_yield()
    {
        return $this->hasMany(ProduceYield::class);
    }
}
