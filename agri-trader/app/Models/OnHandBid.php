<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnHandBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_order_id',
        'on_hand_bid_qty',
        'on_hand_bid_total'
    ];

    public function bid_order()
    {
        return $this->belongsTo(BidOrder::class, 'bid_order_id');
    }
}
