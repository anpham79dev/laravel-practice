<?php

namespace App\Models\Logic;

use App\Models\RentalItem;
use App\Models\ShopBranch;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ShopLogic
{
    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(ShopBranch::class);
    }
}
