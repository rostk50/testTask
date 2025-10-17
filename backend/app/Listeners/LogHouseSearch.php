<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\HouseSearchPerformed;
use Illuminate\Support\Facades\Log;

final class LogHouseSearch
{
    public function handle(HouseSearchPerformed $event): void
    {
        $d = $event->dto;

        Log::channel('search')->info('house_search', [
            'name' => $d->name,
            'bedrooms' => $d->bedrooms,
            'bathrooms' => $d->bathrooms,
            'storeys' => $d->storeys,
            'garages' => $d->garages,
            'price_from' => $d->priceFrom,
            'price_to' => $d->priceTo,
            'sort' => $d->sort,
            'dir' => $d->dir,
            'page' => $d->page,
            'per_page' => $d->perPage,
            'total' => $event->total,
        ]);
    }
}
