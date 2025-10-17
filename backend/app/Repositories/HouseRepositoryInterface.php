<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\House;
use App\Support\DTO\HouseSearchDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface HouseRepositoryInterface
{
    /** @return Builder<House> */
    public function query(HouseSearchDTO $dto): Builder;

    /** @return EloquentCollection<int, House> */
    public function search(HouseSearchDTO $dto): EloquentCollection;
}
