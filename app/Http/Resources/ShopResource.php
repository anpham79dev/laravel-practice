<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'shop_name'   => $this->shop_name,
            'owner_name'  => $this->owner_name,
            'owner_phone' => $this->owner_phone,
            'owner_email' => $this->owner_email,
            'description' => $this->description,
            'is_active'   => $this->is_active,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'branches'    => ShopBranchResource::collection($this->whenLoaded('branches')),
        ];
    }
}
