<?php

namespace Modules\Finance\Repositories\Eloquent;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Booking;
use Modules\Finance\Models\BuyerRequest;
use Modules\Finance\Models\PayoutHistory;
use Modules\Finance\Repositories\Contracts\FinanceRepositoryInterface;

class FinanceRepository implements FinanceRepositoryInterface
{
    /**
     * Get the list of refunds.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refundList(Request $request): JsonResponse
    {
        try {
            $bookings = Booking::with(['gigs', 'user']) // Changed 'userInfo' to 'user'
                ->whereIn('booking_status', [Booking::$refund, Booking::$refundCompleted])
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'code' => 200,
                'message' => 'Refund Bookings Retrieved Successfully',
                'data' => $bookings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong while fetching refund bookings.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Upload refund proof.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadRefundProof(Request $request): JsonResponse
    {
        try {
            $booking = Booking::where('id', $request->bookingid)->first();

            if (! $booking) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Booking not found.',
                ], 404);
            }

            // Upload file
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filePath = $file->store('uploads/payment_proof', 'public');
                $booking->payment_proof = $filePath;
            }

            $booking->booking_status = Booking::$refundCompleted;
            $booking->save();

            return response()->json([
                'code' => 200,
                'message' => 'Refund proof uploaded and booking updated successfully.',
                'data' => $booking,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Something went wrong while processing the refund.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Get buyer transaction details.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function buyerTransaction(Request $request): JsonResponse
    {
        try {
            $providerId = $request->input('provider_id', null);
            $commissionRate = getGeneralSetting('commission', 1);

            $query = Booking::with(['gigs'])
                ->where('booking_status', 4)
                ->when($providerId, function ($query, $providerId) {
                    $query->whereHas('gigs', function ($q) use ($providerId) {
                        $q->where('user_id', $providerId);
                    });
                });

            $transactions = $query->get();

            $validTransactions = $transactions->filter(function ($transaction) {
                return optional($transaction->gigs)->user_id !== null;
            });

            $providerDetailsQuery = User::query()->where('user_type', 3);
            if ($providerId) {
                $providerDetailsQuery->where('id', $providerId);
            }
            $providers = $providerDetailsQuery->get();

            $response = [];

            foreach ($providers as $provider) {
                $providerTransactions = $validTransactions->filter(function ($transaction) use ($provider) {
                    return optional($transaction->gigs)->user_id === $provider->id;
                });

                $totalBookings = $providerTransactions->count();
                $totalGrossAmount = 0;
                $totalCommission = 0;
                $totalReducedAmount = 0;

                foreach ($providerTransactions as $booking) {
                    $grossAmount = $booking->final_price ?? 0;
                    $commissionAmount = $grossAmount * $commissionRate / 100;
                    $reducedAmount = $grossAmount - $commissionAmount;

                    $totalGrossAmount += $grossAmount;
                    $totalCommission += $commissionAmount;
                    $totalReducedAmount += $reducedAmount;
                }

                $enteredAmount = PayoutHistory::where('user_id', $provider->id)->sum('process_amount');

                $pendingBuyerRequestAmount = BuyerRequest::where('provider_id', $provider->id)
                    ->where('status', 0)
                    ->sum('amount');

                $remainingAmount = $totalReducedAmount - $enteredAmount;
                $availableBalance = $remainingAmount - $pendingBuyerRequestAmount;

                $currencySymbol = getDefaultCurrencySymbol();

                $profileImage = $provider->userDetails->profile_image ?? '';
                if (! empty($profileImage)) {
                    $profileImage = file_exists(public_path('storage/' . $profileImage))
                        ? url('storage/' . $profileImage)
                        : asset('backend/assets/img/profile-default.png');
                } else {
                    $profileImage = asset('backend/assets/img/profile-default.png');
                }

                $response[] = [
                    'provider' => [
                        'id' => $provider->id,
                        'name' => ucfirst($provider->name ?? ''),
                        'email' => $provider->email,
                        'profile_image' => $profileImage,
                    ],
                    'transactions' => [
                        'total_bookings' => $totalBookings,
                        'total_gross_amount' => $totalGrossAmount,
                        'total_commission_amount' => $totalCommission,
                        'total_reduced_amount' => $totalReducedAmount,
                        'entered_amount' => $enteredAmount,
                        'pending_request_amount' => $pendingBuyerRequestAmount,
                        'remaining_amount' => $remainingAmount,
                        'available_balance' => $availableBalance,
                        'commission_rate' => $commissionRate,
                    ],
                    'currencySymbol' => $currencySymbol,
                ];
            }

            usort($response, function ($a, $b) {
                return $b['transactions']['total_bookings'] <=> $a['transactions']['total_bookings'];
            });

            return response()->json([
                'success' => true,
                'message' => 'Provider transactions retrieved successfully.',
                'data' => $response,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving provider transactions.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function storePayoutHistroy(Request $request): JsonResponse
    {
        try {
            $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

            $payoutHistory = PayoutHistory::create([
                'type' => 1,
                'user_id' => $request->provider_id,
                'total_bookings' => $request->total_bookings,
                'total_earnings' => $request->total_earnings,
                'admin_earnings' => $request->admin_earnings,
                'pay_due' => $request->provider_pay_due,
                'process_amount' => $request->entered_amount,
                'payment_proof' => $paymentProofPath,
                'remaining_amount' => $request->remaining_amount,
            ]);

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Payout history stored successfully.',
                'data' => $payoutHistory,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'error' => true,
                'message' => 'An error occurred while processing the request. Please try again later.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function storeBuyerWithdraw(Request $request): JsonResponse
    {
        try {
            $paymentTypeMap = [
                'paypal' => 1,
                'stripe' => 2,
            ];

            $paymentType = strtolower($request->payment_type);
            $paymentId = $paymentTypeMap[$paymentType] ?? null;

            if (! $paymentId) {
                return response()->json([
                    'code' => 400,
                    'message' => __('Invalid payment type selected.'),
                ]);
            }

            $buyerRequest = BuyerRequest::create([
                'provider_id' => Auth::guard('web')->id(),
                'payment_id' => $paymentId,
                'amount' => $request->amount,
                'status' => 0,
            ]);

            return response()->json([
                'code' => 200,
                'message' => __('Withdrawal request submitted successfully.'),
                'data' => $buyerRequest,
            ]);
        } catch (\Throwable $e) {
            Log::error('Buyer withdraw failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'code' => 500,
                'message' => __('web.common.default_error'),
            ], 500);
        }
    }

    public function getBuyerWithdrawList(Request $request): JsonResponse
    {
        try {
            $providerId = Auth::guard('web')->id();

            $withdrawRequests = BuyerRequest::where('provider_id', $providerId)
                ->orderBy('created_at', 'desc')
                ->get();

            $balanceData = $this->getProviderAvailableBalance();

            return response()->json([
                'code' => 200,
                'message' => __('Withdrawal requests fetched successfully.'),
                'data' => [
                    'available_balance' => $balanceData['available_balance'] ?? 0,
                    'currency_symbol' => $balanceData['currency_symbol'] ?? '',
                    'withdraw_requests' => $withdrawRequests,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch buyer withdrawal requests: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'code' => 500,
                'message' => __('web.common.default_error'),
            ], 500);
        }
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

    public function getBuyerWithdrawListAdmin(Request $request): JsonResponse
    {
        try {
            if (Auth::guard('admin')->check()) {
                // Get withdrawal requests with user and user_details joined
                $withdrawRequests = BuyerRequest::with([
                    'provider:id,name',
                    'provider.userDetail:first_name,last_name,profile_image',
                ])->orderBy('created_at', 'desc')->get();

                $balanceData = $this->getProviderAvailableBalance();

                return response()->json([
                    'code' => 200,
                    'message' => __('Withdrawal requests fetched successfully.'),
                    'data' => [
                        'available_balance' => $balanceData['available_balance'] ?? 0,
                        'currency_symbol' => $balanceData['currency_symbol'] ?? '',
                        'withdraw_requests' => $withdrawRequests,
                    ],
                ]);
            }
            return response()->json([
                'code' => 401,
                'message' => __('Unauthorized access.'),
            ], 401);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch buyer withdrawal requests: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return response()->json([
                'code' => 500,
                'message' => __('web.common.default_error'),
            ], 500);
        }
    }

    public function updateProviderRequest(Request $request): JsonResponse
    {
        try {
            $providerRequest = BuyerRequest::where('id', $request->id)->first();
            if (! $providerRequest) {
                return response()->json([
                    'code' => 404,
                    'message' => __('No pending request found for the provider.'),
                    'data' => null,
                ], 404);
            }

            $filePath = $request->file('payment_proof')->store('payment_proofs', 'public');
            if ($filePath === false) {
                $filePath = null;
            }

            $providerRequest->status = 1;
            $providerRequest->payment_proof_path = $filePath;
            $providerRequest->save();

            $providerId = $request->provider_id;
            $commissionRate = getGeneralSetting('commission', 1);

            $bookings = Booking::with('gigs')
                ->where('booking_status', 4)
                ->whereHas('gigs', function ($q) use ($providerId) {
                    $q->where('user_id', $providerId);
                })
                ->get();

            $totalBookings = $bookings->count();
            $totalGrossAmount = 0;
            $totalCommissionAmount = 0;

            foreach ($bookings as $booking) {
                $finalPrice = $booking->final_price ?? 0;
                $commission = $finalPrice * $commissionRate / 100;

                $totalGrossAmount += $finalPrice;
                $totalCommissionAmount += $commission;
            }

            $totalReducedAmount = $totalGrossAmount - $totalCommissionAmount;
            $enteredAmount = PayoutHistory::where('user_id', $providerId)->sum('process_amount');
            $remainingAmount = $totalReducedAmount - $enteredAmount;

            PayoutHistory::create([
                'user_id' => $providerId,
                'type' => 1,
                'total_bookings' => $totalBookings,
                'total_earnings' => $totalGrossAmount,
                'admin_earnings' => $totalCommissionAmount,
                'pay_due' => $totalReducedAmount,
                'process_amount' => $request->provider_amount,
                'payment_proof' => $filePath,
                'remaining_amount' => $remainingAmount - $request->provider_amount,
            ]);

            return response()->json([
                'code' => 200,
                'message' => __('Provider request updated and payout history recorded successfully.'),
                'data' => [
                    'total_bookings' => $totalBookings,
                    'total_earnings' => $totalGrossAmount,
                    'admin_earnings' => $totalCommissionAmount,
                    'pay_due' => $totalReducedAmount,
                    'processed_amount' => $request->provider_amount,
                    'remaining_amount' => $remainingAmount - $request->provider_amount,
                    'payment_proof_path' => $filePath,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('An error occurred while updating the provider request.'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
