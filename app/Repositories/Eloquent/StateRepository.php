<?php

namespace App\Repositories\Eloquent;

use App\Models\Country;
use App\Models\State;
use App\Repositories\Contracts\StateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Page\Http\Requests\StoreStateRequest;

class StateRepository implements StateRepositoryInterface
{
    /**
     * @return View
     */
    public function index(): View
    {
        $country_ids = Country::select('id', 'name')->get();
        return view('admin.state.index', compact('country_ids'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreStateRequest $request): JsonResponse
    {
        $id = $request->id ?? null;

        $successMsg = $id === null ? __('admin.cms.state_create_success') : __('admin.cms.state_update_success');
        $errorMsg = $id === null ? __('admin.common.default_create_error') : __('admin.common.default_update_error');

        try {
            $data = [
                'name' => $request->name,
                'country_id' => $request->country_id,
                'status' => $request->status ?? 1,
            ];

            if ($id === null) {
                State::create($data);
            } else {
                State::where('id', $id)->update($data);
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
            ], 500);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $orderBy = $request->order_by ?? 'desc';

        try {
            $data = State::with('country')
                ->when($request->search, function ($query) use ($request) {
                    $query->where('name', 'LIKE', "%{$request->search}%");
                })
                ->when($request->filled('status'), function ($query) use ($request) {
                    $query->where('status', $request->status);
                })
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        $state = State::find($request->id);

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $state,
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            State::where('id', $request->id)->delete();

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => __('admin.cms.state_delete_success'),
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        if (! $request->ids || count($request->ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected.',
            ]);
        }

        State::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => __('admin.cms.state_delete_success'),
        ]);
    }
}
