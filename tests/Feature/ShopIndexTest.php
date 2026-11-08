<?php

namespace Tests\Feature;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_without_parameters_has_default_pagination_and_stable_order(): void
    {
        $latest = null;

        for ($i = 0; $i < 16; $i++) {
            $latest = $this->createShop(['created_at' => '2026-11-08 00:00:00']);
        }

        $this->getJson('/api/shop')
            ->assertOk()
            ->assertJsonCount(15, 'data')
            ->assertJsonPath('data.0.id', $latest->id)
            ->assertJsonPath('meta.per_page', 15)
            ->assertJsonPath('meta.total', 16)
            ->assertJsonStructure(['data' => [['id', 'shop_name', 'created_at', 'updated_at']]]);
    }

    public function test_search_zero_and_inactive_filter_are_preserved(): void
    {
        $match = $this->createShop(['shop_name' => 'Camera 0', 'is_active' => false]);
        $this->createShop(['shop_name' => 'Camera 1', 'is_active' => false]);
        $this->createShop(['shop_name' => 'Camera 0', 'is_active' => true]);

        $this->getJson('/api/shop?search=0&is_active=0')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $match->id)
            ->assertJsonPath('data.0.is_active', false);
    }

    public function test_name_sort_uses_id_to_break_ties_and_links_keep_filters(): void
    {
        $first = $this->createShop(['shop_name' => 'Camera A']);
        $second = $this->createShop(['shop_name' => 'Camera A']);
        $this->createShop(['shop_name' => 'Camera B']);

        $response = $this->getJson('/api/shop?search=Camera&sort=shop_name&direction=asc&per_page=1');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $first->id);

        parse_str(parse_url($response->json('links.next'), PHP_URL_QUERY), $query);

        $this->assertSame('Camera', $query['search']);
        $this->assertSame('shop_name', $query['sort']);
        $this->assertSame('asc', $query['direction']);
        $this->assertSame('1', $query['per_page']);

        $this->getJson('/api/shop?' . http_build_query($query))
            ->assertOk()
            ->assertJsonPath('data.0.id', $second->id);
    }

    public function test_invalid_filters_return_validation_errors(): void
    {
        foreach ([
            ['sort' => 'owner_phone'],
            ['direction' => 'invalid'],
            ['per_page' => 101],
            ['per_page' => 0],
            ['page' => 0],
            ['search' => ['invalid']],
            ['is_active' => 'invalid'],
        ] as $filters) {
            $this->getJson('/api/shop?' . http_build_query($filters))
                ->assertUnprocessable()
                ->assertJsonValidationErrors(array_keys($filters));
        }
    }

    public function test_empty_list_and_unmatched_search_return_empty_data(): void
    {
        $this->getJson('/api/shop')
            ->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);

        $this->createShop();

        $this->getJson('/api/shop?search=missing')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    private function createShop(array $attributes = []): Shop
    {
        return Shop::create(array_replace([
            'shop_name' => 'Test Shop',
            'owner_name' => 'Test Owner',
            'owner_phone' => '0900000000',
        ], $attributes));
    }
}
