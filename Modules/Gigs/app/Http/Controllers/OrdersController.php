<?php

namespace Modules\Gigs\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Gigs\Repositories\Eloquent\OrderRepository;

class OrdersController extends Controller
{
    protected $orderRepository;
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }
    public function indexSeller(): View
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        return view('frontend.seller.order.index', [
            'seller_id' => $user->id,
        ]);
    }

    public function orderList(Request $request): JsonResponse
    {
        $response = $this->orderRepository->orderList($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function orderDetails(Request $request): JsonResponse
    {
        $response = $this->orderRepository->orderDetails($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function orderfile(Request $request): JsonResponse
    {
        $response = $this->orderRepository->orderfile($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function orderStatus(Request $request): JsonResponse
    {
        $response = $this->orderRepository->orderStatus($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function buyerList(Request $request): JsonResponse
    {
        $response = $this->orderRepository->buyerList($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    // Buyers Functions
    public function indexBuyser(): View
    {
        /** @var User|null $user */
        $user = auth()->user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        return view('frontend.buyer.purchase.index', [
            'buyer_id' => $user->id,
        ]);
    }

    public function purchaseList(Request $request): JsonResponse
    {
        $response = $this->orderRepository->purchaseList($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function purchaseDetails(Request $request): JsonResponse
    {
        $response = $this->orderRepository->purchaseDetails($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function buyerOrderfile(Request $request): JsonResponse
    {
        $response = $this->orderRepository->buyerOrderfile($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function purchaseDelate(Request $request): JsonResponse
    {
        $response = $this->orderRepository->purchaseDelate($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function sellerList(Request $request): JsonResponse
    {
        $response = $this->orderRepository->sellerList($request);
        return response()->json($response, $response['code'] ?? 200);
    }
}
