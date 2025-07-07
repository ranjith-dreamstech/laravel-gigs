<?php

namespace App\Repositories\Eloquent;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Repositories\Contracts\CountryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Page\Http\Requests\StoreCountryRequest;

class CountryRepository implements CountryRepositoryInterface
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('admin.country.index');
    }

    /**
     * @return JsonResponse
     */
    public function store(StoreCountryRequest $request): JsonResponse
    {
        $id = $request->id ?? null;

        $successMsg = empty($id)
            ? __('admin.cms.country_create_success')
            : __('admin.cms.country_update_success');

        $errorMsg = empty($id)
            ? __('admin.common.default_create_error')
            : __('admin.common.default_update_error');

        try {
            $data = [
                'name' => $request->name,
                'code' => $request->code,
                'status' => $request->status ?? 1,
            ];

            if (empty($id)) {
                Country::create($data);
            } else {
                Country::where('id', $id)->update($data);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => $successMsg,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $errorMsg,
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $orderBy = $request->order_by ?? 'desc';

        try {
            $data = Country::when($request->search, fn ($q) => $q->where('name', 'LIKE', "%{$request->search}%"))
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->orderBy('id', $orderBy)
                ->get();

            return response()->json([
                'code' => 200,
                'message' => __('admin.common.default_retrieve_success'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.common.default_retrieve_error'),
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        $country = Country::find($request->id);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $country,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            Country::where('id', $request->id)->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.cms.country_delete_success'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => __('admin.common.default_delete_error'),
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        if (! $request->ids || count($request->ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected.',
            ]);
        }

        Country::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => __('admin.cms.country_delete_success'),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getCountries(): JsonResponse
    {
        $countries = Country::select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $countries,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getStates(Request $request): JsonResponse
    {
        $states = State::where('country_id', $request->country_id)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $states,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getCities(Request $request): JsonResponse
    {
        $cities = City::where('state_id', $request->state_id)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $cities,
        ]);
    }
}
