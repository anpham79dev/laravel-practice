<?php

namespace Tests\Feature;

use App\Models\ShopBranch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class ShopStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_a_shop_and_its_first_branch(): void
    {
        $payload = $this->payload();

        $response = $this->postJson('/api/shop', $payload)
            ->assertCreated()
            ->assertJsonPath('data.shop_name', $payload['shop_name']);

        $this->assertDatabaseCount('shops', 1);
        $this->assertDatabaseCount('shop_branches', 1);
        $this->assertDatabaseHas('shop_branches', [
            'shop_id'     => $response->json('data.id'),
            'branch_name' => $payload['branches'][0]['branch_name'],
            'address'     => $payload['branches'][0]['address'],
            'is_active'   => true,
        ]);

        $branch = ShopBranch::firstOrFail();

        $this->assertSame('10.1234567', $branch->latitude);
        $this->assertSame('106.1234567', $branch->longitude);
    }

    public function test_missing_required_fields_are_rejected_without_writing_data(): void
    {
        $this->postJson('/api/shop', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'shop_name', 'owner_name', 'owner_phone', 'branches',
            ]);

        $this->assertDatabaseCount('shops', 0);
        $this->assertDatabaseCount('shop_branches', 0);
    }

    public function test_invalid_contact_details_and_coordinates_are_rejected(): void
    {
        foreach ([
            ['owner_email' => 'invalid'],
            ['owner_phone' => 901234567],
            ['owner_phone' => str_repeat('1', 21)],
            ['is_active' => 'invalid'],
            ['branches.0.address' => str_repeat('a', 501)],
            ['branches.0.latitude' => 91],
            ['branches.0.longitude' => -181],
            ['branches.0.longitude' => null],
        ] as $changes) {
            $payload = $this->payload();

            foreach ($changes as $field => $value) {
                data_set($payload, $field, $value);
            }

            $this->postJson('/api/shop', $payload)
                ->assertUnprocessable()
                ->assertJsonValidationErrors(array_keys($changes));
        }

        $this->assertDatabaseCount('shops', 0);
    }

    public function test_coordinates_can_be_omitted_and_optional_contacts_can_be_null(): void
    {
        $payload = $this->payload();
        $payload['owner_email'] = null;
        $payload['branches'][0]['phone'] = null;
        unset($payload['branches'][0]['latitude'], $payload['branches'][0]['longitude']);

        $this->postJson('/api/shop', $payload)->assertCreated();

        $branch = ShopBranch::firstOrFail();

        $this->assertNull($branch->latitude);
        $this->assertNull($branch->longitude);
    }

    public function test_payload_cannot_assign_a_branch_to_another_shop(): void
    {
        $payload = $this->payload();
        $payload['branches'][0]['shop_id'] = 999;

        $this->postJson('/api/shop', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('branches.0');

        $this->assertDatabaseCount('shops', 0);
        $this->assertDatabaseCount('shop_branches', 0);
    }

    public function test_failed_branch_creation_rolls_back_the_shop(): void
    {
        ShopBranch::creating(function () {
            throw new RuntimeException('Branch creation failed');
        });

        $this->withoutExceptionHandling();

        try {
            $this->postJson('/api/shop', $this->payload());
            $this->fail('Expected branch creation to fail.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Branch creation failed', $exception->getMessage());
        }

        $this->assertDatabaseCount('shops', 0);
        $this->assertDatabaseCount('shop_branches', 0);
    }

    private function payload(): array
    {
        return [
            'shop_name'   => 'Camera Shop',
            'owner_name'  => 'Shop Owner',
            'owner_phone' => '0901234567',
            'owner_email' => 'owner@example.test',
            'branches'    => [[
                'branch_name' => 'Main Branch',
                'address'     => '123 Test Street',
                'latitude'    => '10.1234567',
                'longitude'   => '106.1234567',
            ]],
        ];
    }
}
