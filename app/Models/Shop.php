<?php

namespace App\Models;

use App\Models\Logic\ShopLogic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use ShopLogic, SoftDeletes;

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
