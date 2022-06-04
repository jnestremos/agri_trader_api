<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidOrderAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_order_id',
        'bid_order_acc_type',
        'bid_order_acc_paymentMethod',
        'bid_order_acc_bankName',
        'bid_order_acc_accNum',
        'bid_order_acc_accName',
        'bid_order_acc_amount',
        'bid_order_acc_remarks',
        'bid_order_acc_datePaid'
    ];

    public function bid_order()
    {
        return $this->belongsTo(BidOrder::class, 'bid_order_id');
    }
}
