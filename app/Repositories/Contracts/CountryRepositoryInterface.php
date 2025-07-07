<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Page\Http\Requests\StoreCountryRequest;

interface CountryRepositoryInterface
{
    /** @return View */
    public function index(): View;
    /** @return JsonResponse */
    public function store(StoreCountryRequest $request): JsonResponse;
    /** @return JsonResponse */
    public function list(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function edit(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function delete(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function bulkDelete(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function getCountries(): JsonResponse;
    /** @return JsonResponse */
    public function getStates(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function getCities(Request $request): JsonResponse;
}
