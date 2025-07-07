<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\CityRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Page\Http\Requests\StoreCityRequest;

class CityController extends Controller
{
    protected CityRepositoryInterface $cityRepository;

    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function index(): View
    {
        $state_ids = $this->cityRepository->index();
        return view('admin.city.index', compact('state_ids'));
    }

    public function store(StoreCityRequest $request): JsonResponse
    {
        return $this->cityRepository->store($request);
    }

    public function list(Request $request): JsonResponse
    {
        return $this->cityRepository->list($request);
    }

    public function edit(Request $request): JsonResponse
    {
        return $this->cityRepository->edit($request);
    }

    public function delete(Request $request): JsonResponse
    {
        return $this->cityRepository->delete($request);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        return $this->cityRepository->bulkDelete($request);
    }
}
