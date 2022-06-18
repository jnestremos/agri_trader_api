<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trader_id',
        'distributor_id',
        'project_id',
        'bid_order_status_id',
        'order_grade',
        'order_dateNeededTo',
        'order_dateNeededFrom',
        'order_initialPrice',
        'order_negotiatedPrice',
        'order_finalQty',
        'order_finalPrice',
        'order_finalTotal',
        'order_dpDueDate',
    ];


    public function trader()
    {
        return $this->belongsTo(Trader::class, 'trader_id');
    }
    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
    public function project_bid()
    {
        return $this->hasOne(ProjectBid::class);
    }
    public function on_hand_bid()
    {
        return $this->hasOne(OnHandBid::class);
    }
    public function bid_order_status()
    {
        return $this->hasOne(BidOrderStatus::class, 'bid_order_status_id');
    }
    public function bid_order_account()
    {
        return $this->hasMany(BidOrderAccount::class, 'bid_order_account_id');
    }
    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }
    public function sale()
    {
        return $this->hasMany(Sale::class, 'sale_id');
    }
}
