<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Page\Http\Requests\StoreStateRequest;

interface StateRepositoryInterface
{
    /** @return View */
    public function index(): View;
    /** @return JsonResponse */
    public function store(StoreStateRequest $request): JsonResponse;
    /** @return JsonResponse */
    public function list(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function edit(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function delete(Request $request): JsonResponse;
    /** @return JsonResponse */
    public function bulkDelete(Request $request): JsonResponse;
}
