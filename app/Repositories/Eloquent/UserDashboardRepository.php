<?php

namespace App\Repositories\Eloquent;

use App\Models\Gigs;
use App\Models\Notification;
use App\Models\OrderData;
use App\Models\WalletHistory;
use App\Repositories\Contracts\UserDashboardRepositoryInterface;
use Carbon\Carbon;
use Modules\Booking\Models\Booking;
use Modules\Finance\Models\PayoutHistory;

class UserDashboardRepository implements UserDashboardRepositoryInterface
{
    /**
     * @return array <string, mixed>
     */
    public function getBuyerDashboardData(int $userId): array
    {
        $ordersCount = [
            'active' => Booking::where('customer_id', $userId)->where('booking_status', Booking::$inprogress)->count(),
            'pending' => Booking::where('customer_id', $userId)->where('booking_status', Booking::$pending)->count(),
            'completed' => Booking::withTrashed()->where('customer_id', $userId)->where('booking_status', Booking::$completed)->count(),
        ];

        $totalSpentAmount = formatPrice(
            (float) Booking::withTrashed()
                ->where('customer_id', $userId)
                ->where('booking_status', Booking::$completed)
                ->sum('final_price')
        );

        // Wallet Calculations
        $currentWeekCredit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '1')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $lastWeekCredit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '1')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->sum('amount');

        $currentWeekDebit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '2')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $lastWeekDebit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '2')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->sum('amount');

        $creditPercentage = $lastWeekCredit > 0 ? number_format(($currentWeekCredit - $lastWeekCredit) / $lastWeekCredit * 100, 2) : 0;
        $debitPercentage = $lastWeekDebit > 0 ? number_format(($currentWeekDebit - $lastWeekDebit) / $lastWeekDebit * 100, 2) : 0;

        $totalCredit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '1')->sum('amount');
        $totalDebit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '2')->sum('amount');

        $wallet = [
            'total_credit' => formatPrice((float) $totalCredit),
            'total_debit' => formatPrice((float) $totalDebit),
            'total_balance' => formatPrice($totalCredit - $totalDebit),
            'credit_percentage' => number_format((float) $creditPercentage, 2),
            'debit_percentage' => number_format((float) $debitPercentage, 2),
        ];

        // Recent Orders
        $recentOrders = Booking::with([
            'gig:id,title',
            'seller:id,name',
            'gig.imageMeta:id,gig_id,value,key',
        ])
            ->whereHas('gig')
            ->where('customer_id', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $order->delivery_date = formatDateTime($order->delivery_by, false);
                $order->booking_status_text = Booking::getStatusLabel($order->booking_status);
                $order->final_price = formatPrice($order->final_price);
                unset($order->delivery_by);
                return $order;
            });

        // Recent Files
        $recentFiles = OrderData::select('id', 'data', 'file_type', 'updated_at')
            ->where('created_by', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($file) {
                $file->updated_date = formatDateTime($file->updated_at, false);
                return $file;
            });

        // Recent Payments
        $recentPayments = Booking::with([
            'gig' => function ($q) {
                $q->withTrashed()->select('id', 'title');
            },
            'user:id,name',
            'seller:id,name',
        ])
            ->withTrashed()
            ->where('customer_id', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $order->booking_date = formatDateTime($order->booking_date, false);
                $order->booking_status_text = Booking::getStatusLabel($order->booking_status);
                $order->final_price = formatPrice($order->final_price);
                $order->payment_status_text = Booking::getPaymentStatusLabel($order->payment_status);
                $order->payment_type = ucfirst($order->payment_type);
                unset($order->delivery_by);
                return $order;
            });

        // Notifications
        $recentNotifications = Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                $notification->created_date = Carbon::parse($notification->created_at)->diffForHumans();
                return $notification;
            });

        return compact(
            'ordersCount',
            'totalSpentAmount',
            'wallet',
            'recentOrders',
            'recentFiles',
            'recentPayments',
            'recentNotifications'
        );
    }

    /**
     * @return array <string, mixed>
     */
    public function getSellerDashboardData(int $userId): array
    {
        $gigIds = Gigs::where('user_id', $userId)->pluck('id');

        // Orders Count
        $ordersCount = [
            'active' => Booking::whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$inprogress)->count(),
            'pending' => Booking::whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$pending)->count(),
            'completed' => Booking::withTrashed()->whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$completed)->count(),
            'cancelled' => Booking::withTrashed()->whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$cancelled)->count(),
        ];

        // Orders Amount
        $ordersAmount = [
            'active' => formatPrice((float) Booking::whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$inprogress)->sum('final_price')),
            'pending' => formatPrice((float) Booking::whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$pending)->sum('final_price')),
            'completed' => formatPrice((float) Booking::withTrashed()->whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$completed)->sum('final_price')),
            'cancelled' => formatPrice((float) Booking::withTrashed()->whereIn('gigs_id', $gigIds)->where('booking_status', Booking::$cancelled)->sum('final_price')),
        ];

        // Earnings
        $earnings = formatPrice(
            Booking::withTrashed()
                ->whereIn('gigs_id', $gigIds)
                ->where('booking_status', Booking::$completed)
                ->sum('final_price')
        );

        // Wallet stats
        $currentWeekCredit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '1')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $lastWeekCredit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '1')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->sum('amount');

        $currentWeekDebit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '2')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $lastWeekDebit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '2')->whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->sum('amount');

        $creditPercentage = $lastWeekCredit > 0 ? number_format(($currentWeekCredit - $lastWeekCredit) / $lastWeekCredit * 100, 2) : 0;
        $debitPercentage = $lastWeekDebit > 0 ? number_format(($currentWeekDebit - $lastWeekDebit) / $lastWeekDebit * 100, 2) : 0;

        $totalCredit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '1')->sum('amount');
        $totalDebit = WalletHistory::where('user_id', $userId)->where('status', 'Completed')->where('type', '2')->sum('amount');

        $wallet = [
            'total_credit' => formatPrice((float) $totalCredit),
            'total_debit' => formatPrice((float) $totalDebit),
            'total_balance' => formatPrice((float) ($totalCredit - $totalDebit)),
            'credit_percentage' => number_format((float) $creditPercentage, 2),
            'debit_percentage' => number_format((float) $debitPercentage, 2),
        ];

        // Recent Orders
        $recentOrders = Booking::with([
            'gig:id,title',
            'user:id,name',
            'gig.imageMeta:id,gig_id,value,key',
        ])
            ->whereIn('gigs_id', $gigIds)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $order->delivery_date = formatDateTime($order->delivery_by, false);
                $order->booking_status_text = Booking::getStatusLabel($order->booking_status);
                $order->final_price = formatPrice($order->final_price);
                unset($order->delivery_by);
                return $order;
            });

        // Recent Files
        $recentFiles = OrderData::select('id', 'data', 'file_type', 'updated_at')
            ->whereIn('gigs_id', $gigIds)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($file) {
                $file->updated_date = formatDateTime($file->updated_at, false);
                return $file;
            });

        // Recent Payments
        $recentPayments = Booking::with([
            'gig' => function ($q) {
                $q->withTrashed()->select('id', 'title');
            },
            'user:id,name',
            'seller:id,name',
        ])
            ->withTrashed()
            ->whereIn('gigs_id', $gigIds)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                $order->booking_date = formatDateTime($order->booking_date, false);
                $order->booking_status_text = Booking::getStatusLabel($order->booking_status);
                $order->final_price = formatPrice($order->final_price);
                $order->payment_status_text = Booking::getPaymentStatusLabel($order->payment_status);
                $order->payment_type = ucfirst($order->payment_type);
                unset($order->delivery_by);
                return $order;
            });

        // Recent Notifications
        $recentNotifications = Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                $notification->created_date = Carbon::parse($notification->created_at)->diffForHumans();
                return $notification;
            });

        return compact(
            'ordersCount',
            'ordersAmount',
            'recentOrders',
            'recentFiles',
            'recentPayments',
            'recentNotifications',
            'earnings',
            'wallet'
        );
    }

    /**
     * @return array <string, mixed>
     */
    public function getPaymentsSaleStatistics(int $userId, int $year): array
    {
        $monthlyRevenue = array_fill(1, 12, 0);
        $monthlyWithdrawn = array_fill(1, 12, 0);

        $totalRevenue = Booking::with('gigs')
            ->withTrashed()
            ->where('booking_status', Booking::$completed)
            ->whereYear('created_at', $year)
            ->whereHas('gigs', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->sum('final_price');

        $validBookings = Booking::with('gigs')
            ->withTrashed()
            ->where('booking_status', Booking::$completed)
            ->whereYear('created_at', $year)
            ->whereHas('gigs', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->get();

        foreach ($validBookings as $booking) {
            $month = Carbon::parse($booking->created_at)->month;
            $grossAmount = $booking->final_price ?? 0;

            $monthlyRevenue[$month] += $grossAmount;
        }

        $withdrawals = PayoutHistory::where('user_id', $userId)
            ->whereYear('created_at', $year)
            ->get();

        foreach ($withdrawals as $withdraw) {
            $month = Carbon::parse($withdraw->created_at)->month;
            $monthlyWithdrawn[$month] += $withdraw->process_amount ?? 0;
        }

        $withdrawnAmount = $withdrawals->sum('process_amount');

        return [
            'months' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ],

            'revenue' => array_map(fn ($val) => number_format($val, 2, '.', ''), array_values($monthlyRevenue)),

            'withdrawn' => array_map(fn ($val) => number_format($val, 2, '.', ''), array_values($monthlyWithdrawn)),

            'total_withdrawn_amount' => formatPrice($withdrawnAmount),
            'total_revenue_amount' => formatPrice($totalRevenue),
            'currency' => getDefaultCurrencySymbol(),
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function getGigsSalesStatistics(int $userId, int $year): array
    {
        $gigIds = Gigs::where('user_id', $userId)->pluck('id');

        $gigs = Booking::whereIn('gigs_id', $gigIds)
            ->withTrashed()
            ->where('booking_status', Booking::$completed)
            ->whereYear('created_at', $year)
            ->get();

        $monthlyGigs = array_fill(1, 12, 0);

        foreach ($gigs as $gig) {
            $month = Carbon::parse($gig->created_at)->month;
            $monthlyGigs[$month] += 1;
        }

        return [
            'no_gigs' => array_values($monthlyGigs),
        ];
    }
}
