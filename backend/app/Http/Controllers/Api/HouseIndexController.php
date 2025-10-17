<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\HouseSearchPerformed;
use App\Http\Requests\HousesIndexRequest;
use App\Repositories\HouseRepositoryInterface;
use App\Support\DTO\HouseSearchDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class HouseIndexController extends Controller
{
    public function __construct(private readonly HouseRepositoryInterface $repo) {}

    public function index(HousesIndexRequest $request): JsonResponse
    {
        $dto = HouseSearchDTO::fromArray($request->validated());

        $paginator = $this->repo->query($dto)
            ->orderBy($dto->sort, $dto->dir)
            ->paginate(
                $dto->perPage,
                ['id', 'name', 'price', 'bedrooms', 'bathrooms', 'storeys', 'garages'],
                'page',
                $dto->page
            );
        event(new HouseSearchPerformed($dto, $paginator->total()));

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'sort' => $dto->sort,
                'dir' => $dto->dir,
            ],
        ]);
    }
}
