<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RentalItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'shop_id'              => $this->shop_id,
            'category_id'          => $this->category_id,
            'item_name'            => $this->item_name,
            'description'          => $this->description,
            'rental_price_per_day' => $this->rental_price_per_day,
            'deposit_amount'       => $this->deposit_amount,
            'is_active'            => $this->is_active,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
            'shop'                 => new ShopSummaryResource($this->whenLoaded('shop')),
            'category'             => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
