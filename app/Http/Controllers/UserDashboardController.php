<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserDashboardRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    protected UserDashboardRepositoryInterface $userDashboardRepository;

    public function __construct(UserDashboardRepositoryInterface $userDashboardRepository)
    {
        $this->userDashboardRepository = $userDashboardRepository;
    }

    /**
     * @return View
     */
    public function buyerDashboard(): View
    {
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $userId = $authUser->id;
        $data = $this->userDashboardRepository->getBuyerDashboardData($userId);

        return view('frontend.buyer.dashboard', $data);
    }

    public function sellerDashboard(): View
    {
        /** @var \App\Models\User  $authUser */
        $authUser = current_user();
        $userId = $authUser->id;
        $data = $this->userDashboardRepository->getSellerDashboardData($userId);

        return view('frontend.seller.dashboard', $data);
    }

    public function getPaymentsSaleStatistics(Request $request): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $userId = $authUser->id;
        $year = $request->input('year', now()->year);

        $data = $this->userDashboardRepository->getPaymentsSaleStatistics($userId, $year);

        return response()->json($data, 200);
    }

    public function getGigsSalesStatistics(Request $request): JsonResponse
    {
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $userId = $authUser->id;
        $year = $request->input('year', now()->year);

        $data = $this->userDashboardRepository->getGigsSalesStatistics($userId, $year);

        return response()->json($data, 200);
    }
}
