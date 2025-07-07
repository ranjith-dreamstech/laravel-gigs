<?php

namespace App\Repositories\Contracts;

use App\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Page\Http\Requests\StoreCityRequest;

interface CityRepositoryInterface
{
    /** @return Collection<int, State> */
    public function index(): Collection;
    /** @return JsonResponse */
    public function store(StoreCityRequest $request): JsonResponse;
    /** @return JsonResponse */
    public function list(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function edit(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function delete(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function bulkDelete(Request $request): JsonResponse;
}
