<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\WalletHistory;
use App\Repositories\Contracts\SellerEarningRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;
use Modules\Finance\Models\PayoutHistory;

class SellerEarningRepository implements SellerEarningRepositoryInterface
{
    public function sellerEarningList(Request $request): JsonResponse
    {
        $providerId = Auth::guard('web')->id();
        $commissionRate = getGeneralSetting('commission', 1);

        $query = Booking::with(['gigs', 'gig.imageMeta:id,gig_id,value,key'])
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
        $currencySymbol = getDefaultCurrencySymbol();

        foreach ($providers as $provider) {
            $providerTransactions = $validTransactions->filter(function ($transaction) use ($provider) {
                return optional($transaction->gigs)->user_id === $provider->id;
            });

            $totalBookings = $providerTransactions->count();
            $totalGrossAmount = 0;
            $totalCommission = 0;
            $totalReducedAmount = 0;
            $bookingDetails = [];

            foreach ($providerTransactions as $booking) {
                $grossAmount = $booking->final_price ?? 0;
                $commissionAmount = $grossAmount * $commissionRate / 100;
                $reducedAmount = $grossAmount - $commissionAmount;

                $totalGrossAmount += $grossAmount;
                $totalCommission += $commissionAmount;
                $totalReducedAmount += $reducedAmount;

                $imageMeta = optional($booking->gigs)->imageMeta->where('key', 'image')->first();
                $gigImage = $imageMeta && file_exists(public_path('storage/' . $imageMeta->value))
                    ? url('storage/' . $imageMeta->value)
                    : asset('backend/assets/img/default-image-02.jpg');

                $bookingDetails[] = [
                    'booking_id' => $booking->id,
                    'order_id' => $booking->order_id,
                    'payment_type' => $booking->payment_type,
                    'gig_title' => optional($booking->gigs)->title ?? 'N/A',
                    'gig_image' => $gigImage,
                    'final_price' => round($grossAmount, 2),
                    'commission' => round($commissionAmount, 2),
                    'earning' => round($reducedAmount, 2),
                    'booking_date' => optional($booking->created_at)?->format('Y-m-d H:i:s'),
                    'status' => $booking->booking_status,
                ];
            }

            $enteredAmount = PayoutHistory::where('user_id', $provider->id)->sum('process_amount');
            $remainingAmount = $totalReducedAmount - $enteredAmount;

            $profileImage = $provider->userDetails->profile_image ?? '';
            $profileImage = ! empty($profileImage) && file_exists(public_path('storage/' . $profileImage))
                ? url('storage/' . $profileImage)
                : asset('backend/assets/img/profile-default.png');

            $response[] = [
                'id' => $provider->id,
                'name' => $provider->name,
                'email' => $provider->email,
                'profile_image' => $profileImage,
                'total_bookings' => $totalBookings,
                'total_gross_amount' => round((float) $totalGrossAmount, 2),
                'total_commission' => round((float) $totalCommission, 2),
                'total_earnings' => round((float) $totalReducedAmount, 2),
                'withdrawn' => round((float) $enteredAmount, 2),
                'remaining' => round((float) $remainingAmount, 2),
                'currency_symbol' => $currencySymbol,
                'bookings' => $bookingDetails,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaction summary retrieved successfully',
            'data' => $response,
        ]);
    }

    public function sellerEarningChartData(Request $request): JsonResponse
    {
        $providerId = Auth::guard('web')->id();
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;
        $commissionRate = getGeneralSetting('commission', 1);

        // Initialize daily chart structure
        $dailyData = array_fill(1, $daysInMonth, [
            'total_bookings' => 0,
            'total_gross_amount' => 0,
            'total_commission' => 0,
            'total_earnings' => 0,
        ]);

        // Get confirmed bookings for the month
        $bookings = Booking::with('gigs')
            ->whereHas('gigs', function ($q) use ($providerId) {
                $q->where('user_id', $providerId);
            })
            ->where('booking_status', 4)
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $month)
            ->get();

        $totalBookings = 0;
        $totalGross = 0;
        $totalCommission = 0;
        $totalEarnings = 0;

        foreach ($bookings as $booking) {
            $day = (int) Carbon::parse($booking->booking_date)->format('j');
            $grossAmount = $booking->final_price ?? 0;
            $commissionAmount = $grossAmount * $commissionRate / 100;
            $earning = $grossAmount - $commissionAmount;

            $dailyData[$day]['total_bookings'] += 1;
            $dailyData[$day]['total_gross_amount'] += $grossAmount;
            $dailyData[$day]['total_commission'] += $commissionAmount;
            $dailyData[$day]['total_earnings'] += $earning;

            $totalBookings++;
            $totalGross += $grossAmount;
            $totalCommission += $commissionAmount;
            $totalEarnings += $earning;
        }

        // Prepare response for charting
        $response = [];
        foreach ($dailyData as $day => $data) {
            $response[] = array_merge(['day' => $day], $data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Seller bookings chart data for the month retrieved successfully',
            'data' => $response,
            'totals' => [
                'total_bookings' => $totalBookings,
                'total_gross_amount' => round($totalGross, 2),
                'total_commission' => round($totalCommission, 2),
                'total_earnings' => round($totalEarnings, 2),
            ],
            'currency' => getDefaultCurrencySymbol(),
        ]);
    }

    public function getRecentPayments(Request $request): JsonResponse
    {
        try {
            $userId = optional(current_user())->id;
            $currencySymbol = getDefaultCurrencySymbol();

            $paymentTypes = $request->input('payment_types', []);

            $totalCredit = WalletHistory::where('user_id', $userId)
                ->where('status', 'Completed')
                ->where('type', '1') // Credit
                ->sum('amount');

            $totalDebit = WalletHistory::where('user_id', $userId)
                ->where('status', 'Completed')
                ->where('type', '2') // Debit
                ->sum('amount');

            $recentPaymentsQuery = Booking::with([
                'gig' => function ($q) {
                    $q->withTrashed()->select('id', 'title');
                },
                'user:id,name',
                'seller:id,name',
                'gig.imageMeta:id,gig_id,value,key',
            ])
                ->where('customer_id', $userId)
                ->orderBy('created_at', 'desc');

            if (! empty($paymentTypes)) {
                $recentPaymentsQuery->whereIn('payment_type', $paymentTypes);
            }

            $recentPayments = $recentPaymentsQuery->get()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'transaction_id' => $order->transaction_id,
                    'gigs_id' => $order->gigs_id,
                    'booking_date' => formatDateTime($order->booking_date, false),
                    'booking_status' => $order->booking_status,
                    'booking_status_text' => Booking::getStatusLabel($order->booking_status),
                    'payment_type' => ucfirst($order->payment_type),
                    'payment_status' => $order->payment_status,
                    'payment_status_text' => Booking::getPaymentStatusLabel($order->payment_status),
                    'final_price' => formatPrice($order->final_price),
                    'encrypted_id' => encrypt($order->id),
                    'gig' => [
                        'id' => $order->gig?->id,
                        'title' => $order->gig?->title,
                        'image_meta' => $order->gig?->imageMeta,
                    ],
                    'user' => [
                        'id' => $order->user?->id,
                        'name' => $order->user?->name,
                    ],
                    'seller' => [
                        'id' => $order->seller?->id,
                        'name' => $order->seller?->name,
                    ],
                ];
            });

            return response()->json([
                'code' => 200,
                'message' => __('admin.manage.recent_payments_fetched'),
                'currency_symbol' => $currencySymbol,
                'total_credit' => $totalCredit,
                'total_debit' => $totalDebit,
                'total_balance' => $totalCredit - $totalDebit,
                'data' => $recentPayments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => __('admin.manage.error_fetching_payments'),
                'data' => [],
            ]);
        }
    }
}
