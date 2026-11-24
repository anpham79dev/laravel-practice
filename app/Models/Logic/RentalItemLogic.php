<?php

namespace App\Models\Logic;

use App\Models\Category;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait RentalItemLogic
{
    // relationship
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // scope
    /**
     * @param  array<string, mixed>  $params
     */
    public function scopeForList(Builder $query, array $params): Builder
    {
        return $query->simple()
            ->with(['shop:id,shop_name', 'category:id,category_name,slug'])
            ->byParams($params);
    }

    public function scopeSimple(Builder $query): Builder
    {
        return $query->select([
            'id',
            'shop_id',
            'category_id',
            'item_name',
            'description',
            'rental_price_per_day',
            'deposit_amount',
            'is_active',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function scopeByParams(Builder $query, array $params): Builder
    {
        $query->where('is_active', true)
            ->whereHas('shop', fn (Builder $shop) => $shop->where('is_active', true));

        $search = trim($params['search'] ?? '');

        if ($search !== '') {
            $query->where('item_name', 'like', '%'.$search.'%');
        }

        if (isset($params['category_id'])) {
            $query->where('category_id', $params['category_id']);
        }

        if (isset($params['shop_id'])) {
            $query->where('shop_id', $params['shop_id']);
        }

        $sort = $params['sort'] ?? 'created_at';
        $direction = $params['direction'] ?? 'desc';

        return $query->orderBy($sort, $direction)->orderBy('id', $direction);
    }
}
