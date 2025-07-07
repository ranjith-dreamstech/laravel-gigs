<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface SellerEarningRepositoryInterface
{
    public function sellerEarningList(Request $request): JsonResponse;

    public function sellerEarningChartData(Request $request): JsonResponse;

    public function getRecentPayments(Request $request): JsonResponse;
}
