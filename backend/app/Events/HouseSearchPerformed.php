<?php

declare(strict_types=1);

namespace App\Events;

use App\Support\DTO\HouseSearchDTO;

final class HouseSearchPerformed
{
    public function __construct(
        public HouseSearchDTO $dto,
        public int $total
    ) {}
}
