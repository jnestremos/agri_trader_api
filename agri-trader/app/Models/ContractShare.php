<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'contractShare_type',
        'contractShare_amount'
    ];

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
