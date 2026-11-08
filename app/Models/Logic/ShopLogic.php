<?php

namespace App\Models\Logic;

use App\Models\RentalItem;
use App\Models\ShopBranch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ShopLogic
{
    // relation ship
    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(ShopBranch::class);
    }

    public function scopeSimple(Builder $query): Builder
    {
        return $query->select([
            'id',
            'shop_name',
            'owner_name',
            'owner_phone',
            'owner_email',
            'description',
            'is_active',
            'created_at',
            'updated_at',
        ]);
    }

    // scope
    public function scopeForList(Builder $query, array $params): Builder
    {
        return $query->simple()->byParams($params);
    }

    public function scopeByParams(Builder $query, array $params): Builder
    {
        $search = trim($params['search'] ?? '');

        if ($search !== '') {
            $query->where('shop_name', 'like', '%' . $search . '%');
        }

        if (array_key_exists('is_active', $params)) {
            $query->where('is_active', $params['is_active']);
        }

        $sort      = $params['sort'] ?? 'created_at';
        $direction = $params['direction'] ?? 'desc';

        return $query->orderBy($sort, $direction)->orderBy('id', $direction);
    }
}
