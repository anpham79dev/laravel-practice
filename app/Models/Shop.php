<?php

namespace App\Models;

use App\Models\Logic\ShopLogic;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use ShopLogic;

    protected $table = 'shops';

    protected $fillable = [
        'shop_name',
        'owner_name',
        'owner_phone',
        'owner_email',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
