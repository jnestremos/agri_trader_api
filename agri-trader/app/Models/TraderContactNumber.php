<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderContactNumber extends Model
{
    use HasFactory;
    //protected $table = 'trader_contact_numbers';
    protected $fillable = ['trader_id', 'trader_contactNum'];

    public function trader()
    {
        return $this->belongsTo(Trader::class, 'trader_id');
    }
}
