<?php

namespace Tests\Feature;

use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_accepts_multiple_branches(): void
    {
        $response = $this->postJson('/api/shop', $this->payload([
            ['branch_name' => 'Branch A', 'address' => 'Address A'],
            ['branch_name' => 'Branch B', 'address' => 'Address B'],
        ]))->assertCreated();

        $this->assertSame(2, Shop::findOrFail($response->json('data.id'))->branches()->count());
    }

    public function test_update_edits_existing_branches_adds_new_ones_and_keeps_omitted_branches(): void
    {
        $shop = $this->createShop();
        $branch = $shop->branches()->create(['branch_name' => 'Old Branch', 'address' => 'Old Address']);
        $kept = $shop->branches()->create(['branch_name' => 'Kept Branch', 'address' => 'Kept Address']);

        $this->patchJson('/api/shop/' . $shop->id, $this->payload([
            ['id' => $branch->id, 'branch_name' => 'Updated Branch', 'address' => 'Updated Address'],
            ['branch_name' => 'New Branch', 'address' => 'New Address'],
        ]))->assertOk()->assertJsonPath('data.id', $shop->id);

        $this->assertDatabaseCount('shops', 1);
        $this->assertSame(3, $shop->branches()->count());
        $this->assertSame('Updated Branch', $branch->fresh()->branch_name);
        $this->assertSame('Kept Branch', $kept->fresh()->branch_name);
    }

    public function test_branch_from_another_shop_causes_a_full_rollback(): void
    {
        $shop = $this->createShop();
        $other = $this->createShop();
        $foreign = $other->branches()->create(['branch_name' => 'Foreign Branch', 'address' => 'Other Address']);

        $this->patchJson('/api/shop/' . $shop->id, $this->payload([
            ['branch_name' => 'New Branch', 'address' => 'New Address'],
            ['id' => $foreign->id, 'branch_name' => 'Invalid Edit', 'address' => 'Invalid Address'],
        ]))->assertNotFound();

        $this->assertSame('Original Shop', $shop->fresh()->shop_name);
        $this->assertSame(0, $shop->branches()->count());
        $this->assertSame('Foreign Branch', $foreign->fresh()->branch_name);
    }

    public function test_duplicate_ids_and_empty_branch_lists_are_rejected(): void
    {
        $shop = $this->createShop();
        $branch = $shop->branches()->create(['branch_name' => 'Branch', 'address' => 'Address']);
        $data = ['id' => $branch->id, 'branch_name' => 'Branch', 'address' => 'Address'];

        $this->patchJson('/api/shop/' . $shop->id, $this->payload([$data, $data]))
            ->assertUnprocessable()->assertJsonValidationErrors('branches.0.id');

        $this->patchJson('/api/shop/' . $shop->id, $this->payload([]))
            ->assertUnprocessable()->assertJsonValidationErrors('branches');
    }

    public function test_destroy_soft_deletes_shop_and_returns_no_content(): void
    {
        $shop = $this->createShop();
        $branch = $shop->branches()->create(['branch_name' => 'Branch', 'address' => 'Address']);

        $this->deleteJson('/api/shop/' . $shop->id)->assertNoContent();

        $this->assertSoftDeleted('shops', ['id' => $shop->id]);
        $this->assertDatabaseHas('shop_branches', ['id' => $branch->id]);
        $this->getJson('/api/shop')->assertOk()->assertJsonCount(0, 'data');
        $this->deleteJson('/api/shop/' . $shop->id)->assertNotFound();
    }

    private function createShop(): Shop
    {
        return Shop::create([
            'shop_name' => 'Original Shop',
            'owner_name' => 'Owner',
            'owner_phone' => '0901234567',
        ]);
    }

    private function payload(array $branches): array
    {
        return [
            'shop_name' => 'Updated Shop',
            'owner_name' => 'Owner',
            'owner_phone' => '0901234567',
            'branches' => $branches,
        ];
    }
}
