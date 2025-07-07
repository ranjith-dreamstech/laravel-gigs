<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\WalletRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WalletController extends Controller
{
    protected WalletRepository $walletRepository;

    public function __construct()
    {
        $this->walletRepository = new WalletRepository();
    }

    public function wallet(): View
    {
        return view('frontend.buyer.wallet');
    }

    public function addWallet(Request $request): JsonResponse
    {
        return $this->walletRepository->addWallet($request);
    }

    public function paypalPaymentSuccessWallet(Request $request): JsonResponse|RedirectResponse
    {
        return $this->walletRepository->paypalPaymentSuccessWallet($request);
    }

    public function stripePaymentSuccessWallet(Request $request): JsonResponse|RedirectResponse
    {
        return $this->walletRepository->stripePaymentSuccessWallet($request);
    }

    public function paymentFailed(): RedirectResponse
    {
        return redirect()->route('user.wallet')
            ->with('error', 'Payment failed. Please try again or contact support.');
    }

    public function walletHistoryList(Request $request): JsonResponse
    {
        return $this->walletRepository->walletHistoryList($request);
    }
}
