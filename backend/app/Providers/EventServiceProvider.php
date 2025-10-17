<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\HouseSearchPerformed;
use App\Listeners\LogHouseSearch;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        HouseSearchPerformed::class => [
            LogHouseSearch::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
