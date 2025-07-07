<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

interface WalletRepositoryInterface
{
    public function addWallet(Request $request): JsonResponse;

    public function paypalPaymentSuccessWallet(Request $request): RedirectResponse|JsonResponse;

    public function stripePaymentSuccessWallet(Request $request): RedirectResponse|JsonResponse;

    public function walletHistoryList(Request $request): JsonResponse;
}
