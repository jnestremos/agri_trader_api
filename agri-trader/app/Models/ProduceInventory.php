<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduceInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'produce_yield_id',
        'produce_inventory_qtyOnHand'
    ];

    public function produce_yield()
    {
        return $this->belongsTo(ProduceYield::class, 'produce_yield_id');
    }
}
