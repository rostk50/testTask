<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HouseValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_price_to_cannot_be_less_than_from(): void
    {
        $this->getJson('/api/houses?price_from=500000&price_to=100000')
            ->assertStatus(422);
    }
}
