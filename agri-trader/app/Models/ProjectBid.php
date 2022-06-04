<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBid extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_order_id',
        'project_bid_minQty',
        'project_bid_maxQty',
        'project_bid_total'
    ];

    public function bid_order()
    {
        return $this->belongsTo(BidOrder::class, 'bid_order_id');
    }
}
