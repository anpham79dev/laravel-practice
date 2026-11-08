<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;

class ShopController extends Controller
{
    public function index(ShopRequest $request)
    {
        $params = $request->validated();

        $data = Shop::forList($params)
            ->paginate($params['per_page'] ?? 15)
            ->withQueryString();

        return ShopResource::collection($data);
    }
}
