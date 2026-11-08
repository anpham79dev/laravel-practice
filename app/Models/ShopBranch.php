<?php

namespace App\Models;

use App\Models\Logic\ShopBranchLogic;
use Illuminate\Database\Eloquent\Model;

class ShopBranch extends Model
{
    use ShopBranchLogic;

    protected $fillable = [
        'branch_name',
        'address',
        'phone',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];
}
