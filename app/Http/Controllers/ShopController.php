<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequest;
use App\Http\Requests\StoreShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends Controller
{
    /**
     * @param ShopRequest $request
     */
    public function index(ShopRequest $request)
    {
        $params = $request->validated();

        $data = Shop::forList($params)
            ->paginate($params['per_page'] ?? 15)
            ->withQueryString();

        return ShopResource::collection($data);
    }

    /**
     * @param Shop $shop
     */
    public function show(Shop $shop): ShopResource
    {
        $shop->load('branches');

        return new ShopResource($shop);
    }

    /**
     * @param StoreShopRequest $request
     */
    public function store(StoreShopRequest $request)
    {
        $data = (new Shop)->saveFromRequest($request->validated());

        return (new ShopResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param StoreShopRequest $request
     * @param Shop $shop
     */
    public function update(StoreShopRequest $request, Shop $shop)
    {
        $data = $shop->saveFromRequest($request->validated());

        return new ShopResource($data);
    }

    /**
     * @param Shop $shop
     */
    public function destroy(Shop $shop)
    {
        $shop->delete();

        return response()->noContent();
    }
}
