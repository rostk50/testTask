<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\House;
use App\Support\DTO\HouseSearchDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final class EloquentHouseRepository implements HouseRepositoryInterface
{
    /** @return Builder<House> */
    public function query(HouseSearchDTO $dto): Builder
    {
        $q = House::query();

        if ($dto->name !== null && $dto->name !== '') {
            $value = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $dto->name).'%';
            $q->whereRaw('LOWER(name) LIKE ?', [mb_strtolower($value)]);
        }
        if ($dto->bedrooms !== null) {
            $q->where('bedrooms', $dto->bedrooms);
        }
        if ($dto->bathrooms !== null) {
            $q->where('bathrooms', $dto->bathrooms);
        }
        if ($dto->storeys !== null) {
            $q->where('storeys', $dto->storeys);
        }
        if ($dto->garages !== null) {
            $q->where('garages', $dto->garages);
        }
        if ($dto->priceFrom !== null && $dto->priceTo !== null) {
            $q->whereBetween('price', [$dto->priceFrom, $dto->priceTo]);
        } elseif ($dto->priceFrom !== null) {
            $q->where('price', '>=', $dto->priceFrom);
        } elseif ($dto->priceTo !== null) {
            $q->where('price', '<=', $dto->priceTo);
        }

        return $q;
    }

    /** @return EloquentCollection<int, House> */
    public function search(HouseSearchDTO $dto): EloquentCollection
    {
        return $this->query($dto)
            ->orderBy('id')
            ->get(['id', 'name', 'price', 'bedrooms', 'bathrooms', 'storeys', 'garages']);
    }
}
