<?php

namespace Modules\Gigs\Repositories\Eloquent;

use App\Models\Gigs;
use App\Models\OrderData;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;
use Modules\Gigs\Repositories\Contracts\FileUploadRepositoryInterface;

class FileUploadRepository implements FileUploadRepositoryInterface
{
    /**
     * Get current user's buyer ID
     *
     * @return array{buyer_id: int|null}
     */
    public function index(): array
    {
        /** @var \App\Models\User|null $auth */
        $auth = current_user();

        return ['buyer_id' => $auth?->id];
    }

    /**
     * @return array<string, mixed>
     */
    public function uploadedList(Request $request): array
    {
        $orderBy = $request->get('order_by', 'desc');
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $gigsId = $request->get('gigs_id');
        $type = $request->get('type');

        /** @var User|null $auth */
        $auth = current_user();

        if (! $auth) {
            return [
                'code' => 401,
                'message' => 'Unauthorized',
                'data' => [],
            ];
        }

        try {
            $query = OrderData::query()
                ->where('created_by', $auth->id)
                ->orderBy('id', $orderBy);

            if (! empty($search)) {
                $query->where('order_id', 'LIKE', "%{$search}%");
            }

            if (! empty($startDate) && ! empty($endDate)) {
                $start = Carbon::parse($startDate)->startOfDay();
                $end = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('created_at', [$start, $end]);
            }

            if (! empty($gigsId)) {
                $query->where('gigs_id', $gigsId);
            }

            if (! empty($type)) {
                $query->where('file_type', $type);
            }

            /** @var \Illuminate\Database\Eloquent\Collection<int, OrderData> $data */
            $data = $query->with(['gigs:id,title', 'booking:id,order_id'])->get();

            return [
                'code' => 200,
                'message' => __('admin.general_settings.bank_retrive_success'),
                'data' => $data->toArray(),
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => __('admin.general_settings.retrive_error'),
                'error' => $e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Get list of gigs for a seller
     *
     * @param Request $request
     *
     * @return array<array{id: int, title: string}>
     */
    public function gigsList(Request $request): array
    {
        $sellerId = $request->seller_id;
        $search = $request->search;

        /** @var \Illuminate\Database\Eloquent\Collection<int, Gigs> $gigs */
        $gigs = Gigs::query()
            ->where('user_id', $sellerId)
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->select('id', 'title')
            ->get();

        return $gigs->map(function (\App\Models\Gigs $gig) {
            return [
                'id' => $gig->id,
                'title' => $gig->title,
            ];
        })->all();
    }

    /**
     * Get distinct file types from orders
     *
     * @param Request $request
     *
     * @return SupportCollection<int, string>
     */
    public function orderType(Request $request): SupportCollection
    {
        return OrderData::query()
            ->select('file_type')
            ->distinct()
            ->pluck('file_type')
            ->filter()
            ->values();
    }

    /**
     * @return array<string, mixed>
     */
    public function orderDelete(Request $request): array
    {
        $response = [
            'code' => 200,
            'message' => __('admin.orders.deleted_successfully'),
        ];

        try {
            $orderId = $request->input('order_id');

            if (empty($orderId)) {
                $response['code'] = 422;
                $response['message'] = 'Order ID is required.';
                return $response;
            }

            /** @var OrderData|null $order */
            $order = OrderData::findOrFail($orderId);
            $order->delete();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response['code'] = 404;
            $response['message'] = 'Order not found.';
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = 'Failed to delete order.';
            $response['error'] = $e->getMessage();
        }

        return $response;
    }
}
