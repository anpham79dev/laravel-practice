<?php

namespace App\Models\Logic;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ShopBranchLogic
{
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
