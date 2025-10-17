<?php

declare(strict_types=1);

namespace App\Actions\Houses;

use App\Models\House;
use App\Repositories\HouseRepositoryInterface;
use App\Support\DTO\HouseSearchDTO;
use Illuminate\Support\Collection;

final readonly class HouseSearchAction
{
    public function __construct(private HouseRepositoryInterface $repository) {}

    /** @return Collection<int, House> */
    public function handle(HouseSearchDTO $dto): Collection
    {
        return $this->repository->search($dto);
    }
}
