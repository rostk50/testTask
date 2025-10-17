<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\EloquentHouseRepository;
use App\Repositories\HouseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HouseRepositoryInterface::class, EloquentHouseRepository::class);
    }
}
