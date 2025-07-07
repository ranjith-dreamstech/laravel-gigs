<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Booking\Models\Booking;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = current_user()->id ?? '';

        // Orders Count
        $activeOrdersCount = Booking::where('booking_status', Booking::$inprogress)
            ->count();
        $pendingOrdersCount = Booking::where('booking_status', Booking::$pending)
            ->count();
        $completedOrdersCount = Booking::withTrashed()
            ->where('booking_status', Booking::$completed)
            ->count();
        $cancelledOrdersCount = Booking::withTrashed()
            ->where('booking_status', Booking::$cancelled)
            ->count();

        $ordersCount = [
            'active' => $activeOrdersCount,
            'pending' => $pendingOrdersCount,
            'completed' => $completedOrdersCount,
            'cancelled' => $cancelledOrdersCount,
        ];

        // Orders Amount
        $activeOrdersAmount = Booking::where('booking_status', Booking::$inprogress)
            ->sum('final_price');
        $pendingOrdersAmount = Booking::where('booking_status', Booking::$pending)
            ->sum('final_price');
        $completedOrdersAmount = Booking::withTrashed()
            ->where('booking_status', Booking::$completed)
            ->sum('final_price');
        $cancelledOrdersAmount = Booking::withTrashed()
            ->where('booking_status', Booking::$cancelled)
            ->sum('final_price');

        $ordersAmount = [
            'active' => formatPrice((float) $activeOrdersAmount),
            'pending' => formatPrice((float) $pendingOrdersAmount),
            'completed' => formatPrice((float) $completedOrdersAmount),
            'cancelled' => formatPrice((float) $cancelledOrdersAmount),
        ];

        // Total Reviews
        $totalReviews = Review::where('parent_id', 0)->count();

        // Top Buyers
        $topBuyers = Booking::with([
            'user:id,name',
            'user.userDetails:id,user_id,first_name,last_name,profile_image',
        ])
            ->whereHas('user')
            ->withTrashed()
            ->where('booking_status', Booking::$completed)
            ->select(
                'customer_id',
                DB::raw('COUNT(*) as bookings_count'),
                DB::raw('SUM(final_price) as final_price')
            )
            ->groupBy('customer_id')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get()->map(function ($booking) {
                $userDetail = $booking->user->userDetails ?? null;
                $booking->user->name = $userDetail && $userDetail->first_name ? $userDetail->first_name . ' ' . $userDetail->last_name : $booking->user->name;
                $booking->user->profile_image = $userDetail->profile_image ?? uploadedAsset('', 'profile');
                $booking->final_price = formatPrice($booking->final_price ?? 0);
                unset($booking->user->userDetails);
                return $booking;
            });

        // Recent Orders
        $recentOrders = Booking::with([
            'gig:id,title',
            'user:id,name',
            'seller:id,name',
            'gig.imageMeta:id,gig_id,value,key',
        ])
            ->whereHas('gig')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()->map(function ($order) {
                $order->delivery_date = formatDateTime($order->delivery_by, false);
                $order->booking_status_text = Booking::getStatusLabel($order->booking_status);
                $order->final_price = formatPrice($order->final_price);
                unset($order->delivery_by);
                return $order;
            });

        // Recent Notifications
        $recentNotifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()->map(function ($notification) {
                $notification->created_date = Carbon::parse($notification->created_at)->diffForHumans();
                return $notification;
            });

        // Final Data
        $finalData = compact(
            'ordersCount',
            'ordersAmount',
            'totalReviews',
            'topBuyers',
            'recentOrders',
            'recentNotifications',
        );

        return view('admin.dashboard.index', $finalData);
    }

    public function getIncome(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);
        $previousYear = $year - 1;

        $monthlyIncome = array_fill(1, 12, 0);

        // Current Year Total Income
        $totalIncome = Booking::withTrashed()
            ->where('booking_status', Booking::$completed)
            ->whereYear('created_at', $year)
            ->sum('final_price');

        // Previous Year Total Income
        $previousIncome = Booking::withTrashed()
            ->where('booking_status', Booking::$completed)
            ->whereYear('created_at', $previousYear)
            ->sum('final_price');

        // Income Percentage
        if ($previousIncome === 0 && $totalIncome === 0) {
            $incomePercentage = '0.00';
        } elseif ($previousIncome === 0) {
            $incomePercentage = '100.00';
        } else {
            $incomePercentage = round(($totalIncome - $previousIncome) / $previousIncome * 100, 2);
        }

        // Current Year Valid Bookings
        $validBookings = Booking::withTrashed()
            ->where('booking_status', Booking::$completed)
            ->whereYear('created_at', $year)
            ->get();

        foreach ($validBookings as $booking) {
            $month = Carbon::parse($booking->created_at)->month;
            $grossAmount = $booking->final_price ?? 0;
            $monthlyIncome[$month] += $grossAmount;
        }

        return response()->json([
            'months' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ],
            'revenue' => array_map(function ($value) {
                return number_format($value, 2, '.', '');
            }, array_values($monthlyIncome)),

            'total_income_amount' => formatPrice((float) $totalIncome),
            'income_percentage' => $incomePercentage,
            'currency' => getDefaultCurrencySymbol(),
        ], 200);
    }
}
