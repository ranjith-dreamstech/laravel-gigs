<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;
use Modules\Finance\Http\Requests\FinanceRequest;
use Modules\Finance\Http\Requests\StoreFinanceRequest;
use Modules\Finance\Http\Requests\UpdateProviderRequest;
use Modules\Finance\Models\BuyerRequest;
use Modules\Finance\Models\PayoutHistory;
use Modules\Finance\Repositories\Contracts\FinanceRepositoryInterface;

class FinanceController extends Controller
{
    protected FinanceRepositoryInterface $financeRepository;

    public function __construct(FinanceRepositoryInterface $financeRepository)
    {
        $this->financeRepository = $financeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function earning(): View
    {
        return view('finance::finance.earning');
    }
    public function buyerRequest(): View
    {
        return view('finance::finance.buyer-request');
    }
    public function bookingRefund(): View
    {
        return view('finance::finance.refund');
    }

    public function refundList(Request $request): JsonResponse
    {
        return $this->financeRepository->refundList($request);
    }

    public function uploadRefundProof(FinanceRequest $request): JsonResponse
    {
        return $this->financeRepository->uploadRefundProof($request);
    }

    public function buyerTransaction(Request $request): JsonResponse
    {
        return $this->financeRepository->buyerTransaction($request);
    }
    /**
     * Store a new payout history record.
     *
     * @param \Modules\Finance\Http\Requests\StoreFinanceRequest $request The validated request instance containing payout history data.
     *
     * @return \Illuminate\Http\JsonResponse JSON response indicating the result of the operation.
     */
    public function storePayoutHistroy(StoreFinanceRequest $request): JsonResponse
    {
        return $this->financeRepository->storePayoutHistroy($request);
    }

    public function storeBuyerWithdraw(Request $request): JsonResponse
    {
        return $this->financeRepository->storeBuyerWithdraw($request);
    }
    /**
     * Get the list of withdrawal requests for the authenticated buyer.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getBuyerWithdrawList(Request $request): JsonResponse
    {
        return $this->financeRepository->getBuyerWithdrawList($request);
    }

    public function getBuyerWithdrawListAdmin(Request $request): JsonResponse
    {
        return $this->financeRepository->getBuyerWithdrawListAdmin($request);
    }

    public function updateProviderRequest(UpdateProviderRequest $request): JsonResponse
    {
        return $this->financeRepository->updateProviderRequest($request);
    }

    /**
     * @param int|null $providerId
     *
     * @return array<string, mixed>
     */
    public function getProviderAvailableBalance(?int $providerId = null): array
    {
        try {
            // Fallback to authenticated provider if none passed
            if (! $providerId) {
                $user = Auth::guard('web')->user();

                if (! $user || $user->user_type !== 3) {
                    return [
                        'error' => true,
                        'message' => 'Unauthorized or invalid provider.',
                    ];
                }

                $providerId = $user->id;
            }

            $commissionRate = getGeneralSetting('commission', 1);

            // Get completed bookings for the provider
            $bookings = Booking::with('gigs')
                ->where('booking_status', 4)
                ->whereHas('gigs', function ($q) use ($providerId) {
                    $q->where('user_id', $providerId);
                })
                ->get();

            $validBookings = $bookings->filter(function ($booking) {
                return optional($booking->gigs)->user_id !== null;
            });

            $totalGrossAmount = 0;
            $totalCommission = 0;
            $totalReducedAmount = 0;

            foreach ($validBookings as $booking) {
                $grossAmount = $booking->final_price ?? 0;
                $commissionAmount = $grossAmount * $commissionRate / 100;
                $reducedAmount = $grossAmount - $commissionAmount;

                $totalGrossAmount += $grossAmount;
                $totalCommission += $commissionAmount;
                $totalReducedAmount += $reducedAmount;
            }
            // Sum of already paid out amount
            $enteredAmount = PayoutHistory::where('user_id', $providerId)->sum('process_amount');

            // Sum of all pending withdrawal requests
            $pendingBuyerRequestAmount = BuyerRequest::where('provider_id', $providerId)
                ->where('status', 0)
                ->sum('amount');

            $remainingAmount = $totalReducedAmount - $enteredAmount;

            $availableBalance = $remainingAmount - $pendingBuyerRequestAmount;

            return [
                'available_balance' => $availableBalance,
                'currency_symbol' => getDefaultCurrencySymbol(),
                'details' => [
                    'gross_amount' => $totalGrossAmount,
                    'commission_amount' => $totalCommission,
                    'reduced_amount' => $totalReducedAmount,
                    'paid_amount' => $enteredAmount,
                    'pending_request_amount' => $pendingBuyerRequestAmount,
                    'remaining_amount' => $remainingAmount,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function providerBalance(): JsonResponse
    {
        $balanceData = $this->getProviderAvailableBalance();

        if (isset($balanceData['error'])) {
            return response()->json([
                'success' => false,
                'message' => $balanceData['message'],
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Available balance fetched successfully.',
            'data' => $balanceData,
        ]);
    }
}
