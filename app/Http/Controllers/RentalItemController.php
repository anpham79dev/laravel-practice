<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexRentalItemRequest;
use App\Http\Resources\RentalItemResource;
use App\Models\RentalItem;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RentalItemController extends Controller
{
    public function index(IndexRentalItemRequest $request): AnonymousResourceCollection
    {
        $params = $request->validated();

        $rentalItems = RentalItem::forList($params)
            ->paginate($params['per_page'] ?? 15)
            ->withQueryString();

        return RentalItemResource::collection($rentalItems);
    }
}
