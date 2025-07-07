<?php

namespace Modules\Finance\Repositories\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface FinanceRepositoryInterface
{
    public function refundList(Request $request): JsonResponse;
    public function uploadRefundProof(Request $request): JsonResponse;
    public function buyerTransaction(Request $request): JsonResponse;
    public function storePayoutHistroy(Request $request): JsonResponse;
    public function storeBuyerWithdraw(Request $request): JsonResponse;
    public function getBuyerWithdrawList(Request $request): JsonResponse;
    public function getBuyerWithdrawListAdmin(Request $request): JsonResponse;
    public function updateProviderRequest(Request $request): JsonResponse;
}
