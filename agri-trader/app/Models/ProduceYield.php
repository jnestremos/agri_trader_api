<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduceYield extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'produce_id',
        'produce_yield_class',
        'produce_yield_qtyHarvested',
        'produce_yield_price',
        'produce_yield_unit',
        'produce_yield_dateHarvestFrom',
        'produce_yield_dateHarvestTo'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function produce()
    {
        return $this->belongsTo(Produce::class, 'produce_id');
    }
    public function produce_inventory()
    {
        return $this->hasOne(ProduceInventory::class);
    }
}
