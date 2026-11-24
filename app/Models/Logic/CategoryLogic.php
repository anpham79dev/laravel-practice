<?php

namespace App\Models\Logic;

use App\Models\RentalItem;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CategoryLogic
{
    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }
}
