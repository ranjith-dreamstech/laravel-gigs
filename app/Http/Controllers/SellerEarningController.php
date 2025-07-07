<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\SellerEarningRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerEarningController extends Controller
{
    protected SellerEarningRepository $sellerEarningRepository;

    public function __construct()
    {
        $this->sellerEarningRepository = new SellerEarningRepository();
    }

    public function sellerEarning(): View
    {
        return view('frontend.seller.seller-earnings');
    }

    public function sellerEarningList(Request $request): JsonResponse
    {
        return $this->sellerEarningRepository->sellerEarningList($request);
    }

    public function sellerEarningChartData(Request $request): JsonResponse
    {
        return $this->sellerEarningRepository->sellerEarningChartData($request);
    }

    public function buyerTransaction(): View
    {
        return view('frontend.buyer.buyer-transaction');
    }

    public function getRecentPayments(Request $request): JsonResponse
    {
        return $this->sellerEarningRepository->getRecentPayments($request);
    }
}
