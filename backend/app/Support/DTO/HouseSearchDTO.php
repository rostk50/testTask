<?php

declare(strict_types=1);

namespace App\Support\DTO;

final readonly class HouseSearchDTO
{
    public function __construct(
        public ?string $name,
        public ?int $bedrooms,
        public ?int $bathrooms,
        public ?int $storeys,
        public ?int $garages,
        public ?int $priceFrom,
        public ?int $priceTo,
        public string $sort = 'id',
        public string $dir = 'asc',
        public int $page = 1,
        public int $perPage = 10
    ) {}

    /** @param array<string,mixed> $a */
    public static function fromArray(array $a): self
    {
        $toInt = static function (mixed $v): ?int {
            if ($v === null || $v === '') {
                return null;
            }
            if (is_int($v)) {
                return $v;
            }
            if (is_string($v) && preg_match('/^\d+$/', $v) === 1) {
                return (int) $v;
            }

            return null;
        };

        $posInt = static function (mixed $v, int $min, int $max, int $default) use ($toInt): int {
            $j = $toInt($v) ?? $default;
            if ($j < $min) {
                $j = $min;
            }
            if ($j > $max) {
                $j = $max;
            }

            return $j;
        };

        $sortInput = is_string($a['sort'] ?? null) ? $a['sort'] : 'id';
        $sort = in_array($sortInput, ['id', 'name', 'price', 'bedrooms', 'bathrooms', 'storeys', 'garages'], true) ? $sortInput : 'id';

        $dirInput = is_string($a['dir'] ?? null) ? $a['dir'] : 'asc';
        $dir = $dirInput === 'desc' ? 'desc' : 'asc';

        $page = $posInt($a['page'] ?? null, 1, PHP_INT_MAX, 1);
        $per = $posInt($a['per_page'] ?? null, 1, 100, 10);

        $name = is_string($a['name'] ?? null) && $a['name'] !== '' ? $a['name'] : null;

        $bedrooms = array_key_exists('bedrooms', $a) ? $toInt($a['bedrooms']) : null;
        $bathrooms = array_key_exists('bathrooms', $a) ? $toInt($a['bathrooms']) : null;
        $storeys = array_key_exists('storeys', $a) ? $toInt($a['storeys']) : null;
        $garages = array_key_exists('garages', $a) ? $toInt($a['garages']) : null;
        $priceFrom = array_key_exists('price_from', $a) ? $toInt($a['price_from']) : null;
        $priceTo = array_key_exists('price_to', $a) ? $toInt($a['price_to']) : null;

        return new self(
            $name,
            $bedrooms,
            $bathrooms,
            $storeys,
            $garages,
            $priceFrom,
            $priceTo,
            $sort,
            $dir,
            $page,
            $per
        );
    }
}
