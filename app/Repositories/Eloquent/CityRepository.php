<?php

namespace App\Repositories\Eloquent;

use App\Models\City;
use App\Models\State;
use App\Repositories\Contracts\CityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Page\Http\Requests\StoreCityRequest;

class CityRepository implements CityRepositoryInterface
{
    /**
     * @return Collection<int, State>
     */
    public function index(): Collection
    {
        return State::select('id', 'name')->get();
    }

    /**
     * @return JsonResponse
     */
    public function store(StoreCityRequest $request): JsonResponse
    {
        $id = $request->id ?? null;

        $successMsg = empty($id) ? __('admin.cms.city_create_success') : __('admin.cms.city_update_success');
        $errorMsg = empty($id) ? __('admin.common.default_create_error') : __('admin.common.default_update_error');

        try {
            $data = [
                'name' => $request->name,
                'state_id' => $request->state_id,
                'status' => $request->status ?? 1,
            ];

            if (empty($id)) {
                \App\Models\City::create($data);
            } else {
                \App\Models\City::where('id', $id)->update($data);
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => $successMsg,
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $errorMsg,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $length = $request->input('length');
            $start = $request->input('start');
            $search = $request->input('search');
            $orderByColumnIndex = $request->input('order.0.column');
            $orderByColumn = $request->input("columns.{$orderByColumnIndex}.data") ?? 'name';
            $orderDirection = $request->input('order.0.dir') ?? 'asc';

            $query = City::with(['state.country']);

            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('state', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhereHas('country', function ($qc) use ($search) {
                                $qc->where('name', 'like', "%{$search}%");
                            });
                    });
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $total = $query->count();

            $cities = $query->orderBy($orderByColumn, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $cities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.common.default_retrieve_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        $city = City::find($request->id);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $city,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            City::where('id', $request->id)->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.cms.city_delete_success'),
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
        $ids = $request->ids;

        if (! $ids || count($ids) === 0) {
            return response()->json(['success' => false, 'message' => 'No items selected.']);
        }

        City::whereIn('id', $ids)->delete();

        return response()->json(['success' => true, 'message' => __('admin.cms.city_delete_success')]);
    }
}
