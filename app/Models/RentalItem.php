<?php

namespace App\Models;

use App\Models\Logic\RentalItemLogic;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    use RentalItemLogic;

    protected $fillable = [
        'item_name',
        'description',
        'rental_price_per_day',
        'deposit_amount',
        'is_active',
    ];

    protected $casts = [
        'rental_price_per_day' => 'integer',
        'deposit_amount' => 'integer',
        'is_active' => 'boolean',
    ];
}
