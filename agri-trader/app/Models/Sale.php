<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_order_id',
        'sale_type',
        'sale_qty',
        'sale_stockLeft',
        'sale_price',
        'sale_total'
    ];

    public function bid_order()
    {
        return $this->belongsTo(BidOrder::class, 'bid_order_id');
    }
}
