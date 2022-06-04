<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = ['bid_order_status'];

    public function bid_order()
    {
        return $this->belongsTo(BidOrder::class, 'bid_order_id');
    }
}
