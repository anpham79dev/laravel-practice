<?php

namespace App\Models;

use App\Models\Logic\CategoryLogic;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use CategoryLogic;

    protected $fillable = [
        'category_name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
