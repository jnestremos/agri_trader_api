<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_order_id',
        'delivery_status',
        'delivery_receivedBy',
        'delivery_contactNum',
        'delivery_date'
    ];

    public function bid_order()
    {
        return $this->hasOne(BidOrder::class, 'bid_order_id');
    }
}
