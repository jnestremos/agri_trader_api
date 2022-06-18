<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'bid_order_id',
        'refund_numOfDays',
        'refund_percentage',
        'refund_paymentMethod',
        'refund_amount',
        'refund_receivedBy',
        'refund_contactNum'
    ];

    public function project()
    {
        return $this->hasOne(Project::class, 'project_id');
    }

    public function bid_order()
    {
        return $this->belongsTo(BidOrder::class);
    }
}
