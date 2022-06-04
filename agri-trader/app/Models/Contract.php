<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'trader_id',
        'farm_id',
        'contract_share_id',
        'produce_id',
        'contract_estimatedHarvest',
        'contract_estimatedPrice',
        'contract_estimatedSales',
        'contract_ownerShare',
        'contract_traderShare',
    ];
    public function project()
    {
        return $this->hasOne(Project::class);
    }
    public function trader()
    {
        return $this->belongsTo(Trader::class, 'trader_id');
    }
    public function farm()
    {
        return $this->belongsTo(Farm::class, 'farm_id');
    }
    public function produce()
    {
        return $this->belongsTo(Produce::class, 'produce_id');
    }
    public function contract_share()
    {
        return $this->belongsTo(ContractShare::class);
    }
}
