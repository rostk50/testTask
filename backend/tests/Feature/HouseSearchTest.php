<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\House;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HouseSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        House::insert([
            ['name' => 'The Victoria', 'price' => 374662, 'bedrooms' => 4, 'bathrooms' => 2, 'storeys' => 2, 'garages' => 2],
            ['name' => 'The Xavier', 'price' => 513268, 'bedrooms' => 4, 'bathrooms' => 2, 'storeys' => 1, 'garages' => 2],
            ['name' => 'The Como', 'price' => 454990, 'bedrooms' => 4, 'bathrooms' => 3, 'storeys' => 2, 'garages' => 3],
            ['name' => 'The Aspen', 'price' => 384356, 'bedrooms' => 4, 'bathrooms' => 2, 'storeys' => 2, 'garages' => 2],
            ['name' => 'The Lucretia', 'price' => 572002, 'bedrooms' => 4, 'bathrooms' => 3, 'storeys' => 2, 'garages' => 2],
            ['name' => 'The Toorak', 'price' => 521951, 'bedrooms' => 5, 'bathrooms' => 2, 'storeys' => 1, 'garages' => 2],
            ['name' => 'The Skyscape', 'price' => 263604, 'bedrooms' => 3, 'bathrooms' => 2, 'storeys' => 2, 'garages' => 2],
            ['name' => 'The Clifton', 'price' => 386103, 'bedrooms' => 3, 'bathrooms' => 2, 'storeys' => 1, 'garages' => 1],
            ['name' => 'The Geneva', 'price' => 390600, 'bedrooms' => 4, 'bathrooms' => 3, 'storeys' => 2, 'garages' => 2],
        ]);
    }

    public function test_index_without_filters_returns_all(): void
    {
        $this->getJson('/api/houses')
            ->assertOk()
            ->assertJsonCount(9, 'data')
            ->assertJsonPath('meta.total', 9)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.current_page', 1);
    }

    public function test_partial_name_match(): void
    {
        $this->getJson('/api/houses?name=vic')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'The Victoria');
    }

    public function test_exact_bedrooms_filter(): void
    {
        $this->getJson('/api/houses?bedrooms=3')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_combo_bedrooms_and_bathrooms(): void
    {
        $this->getJson('/api/houses?bedrooms=4&bathrooms=2')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_price_range(): void
    {
        $this->getJson('/api/houses?price_from=300000&price_to=400000')
            ->assertOk()
            ->assertJsonPath('data.0.price', 374662);
    }

    public function test_storeys_and_garages_filters(): void
    {
        $this->getJson('/api/houses?storeys=1')
            ->assertOk()
            ->assertJsonCount(3, 'data');

        $this->getJson('/api/houses?garages=3')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_server_pagination_and_sorting(): void
    {
        $this->getJson('/api/houses?sort=price&dir=desc&per_page=2&page=1')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'The Lucretia')
            ->assertJsonPath('data.1.name', 'The Toorak')
            ->assertJsonPath('meta.total', 9)
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.last_page', 5);

        $this->getJson('/api/houses?sort=price&dir=desc&per_page=2&page=2')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'The Xavier')
            ->assertJsonPath('data.1.name', 'The Como');
    }
}
