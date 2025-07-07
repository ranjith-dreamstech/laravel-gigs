<?php

namespace App\Repositories\Eloquent;

use App\Http\Resources\UserNotification;
use App\Http\Resources\WishlistResource;
use App\Models\Gigs;
use App\Models\Notification;
use App\Models\User;
use App\Models\Wishlist;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Booking\Models\Booking;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @return array <string, mixed>
     */
    public function fetchUserProfile(): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $user = User::with([
            'userDetail',
            'userDetail.country',
            'userDetail.state',
            'userDetail.city',
            'gigs' => function ($query) {
                $query->with(['category', 'meta']);
            },
        ])->where('id', $authUser->id)->first();

        if ($user) {
            if ($user->userDetail && $user->userDetail->dob) {
                $user->userDetail->dob = formatDateTime($user->userDetail->dob, false);
            }
            $createdDate = formatDateTime($user->created_at, false);
            $user->created_date = $createdDate;

            $gigCount = $user->gigs->count();
        } else {
            $gigCount = 0;
        }

        return [
            'user' => $user,
            'gigCount' => $gigCount,
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function mySellerList(Request $request): array
    {
        try {
            /** @var \App\Models\User $authUser */
            $authUser = current_user();
            $userId = $authUser->id;
            $perPage = 9;
            $page = $request->input('page', 1);

            /** @var \Illuminate\Support\Collection<int, \Modules\Booking\Models\Booking> $bookings */
            $bookings = Booking::where('customer_id', $userId)
                ->with(['gig.user.userDetail.country'])
                ->get()
                ->filter(fn ($booking) => $booking->gig !== null && $booking->gig->user_id !== null);

            /** @var \Illuminate\Support\Collection<int, \Illuminate\Support\Collection<\Modules\Booking\Models\Booking>> $grouped */
            $grouped = $bookings->groupBy(fn ($booking) => $booking->gig->user_id);

            $sellerList = $grouped->map(function ($sellerBookings) {
                $user = $sellerBookings->first()->gig->user;
                $userDetail = $user->userDetail;

                $name = $userDetail && $userDetail->first_name
                    ? $userDetail->first_name . ' ' . $userDetail->last_name
                    : $user->name;

                $profileImage = $userDetail ? $userDetail->profile_image : uploadedAsset('', 'profile');
                $jobTitle = $userDetail?->job_title;
                $country = $userDetail?->country
                    ? [
                        'name' => $userDetail->country->name,
                        'flag_image' => url('backend/assets/img/flags/' . strtolower($userDetail->country->code) . '.svg'),
                    ]
                    : null;

                return [
                    'user_id' => $user->id,
                    'name' => $name,
                    'total_gigs' => $sellerBookings->count(),
                    'profile_image' => $profileImage,
                    'job_title' => $jobTitle,
                    'country' => $country,
                ];
            })->values();

            $paginated = $sellerList->forPage($page, $perPage)->values();

            return [
                'code' => 200,
                'data' => $paginated,
                'next_page' => $sellerList->count() > $page * $perPage ? $page + 1 : null,
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'error' => true,
                'message' => 'Something went wrong!',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array <string, mixed>
     */
    public function myBuyerList(Request $request): array
    {
        try {
            /** @var \App\Models\User $authUser */
            $authUser = current_user();
            $userId = $authUser->id;
            $perPage = 9;
            $page = $request->input('page', 1);

            $gigIds = Gigs::where('user_id', $userId)->pluck('id');

            $bookings = Booking::whereIn('gigs_id', $gigIds)
                ->with(['user.userDetail.country'])
                ->get();

            $grouped = $bookings->groupBy(function ($booking) {
                return $booking->customer_id;
            });

            $buyerList = $grouped->map(function ($buyerBookings) {
                $user = $buyerBookings->first()->user;
                $userDetail = $user->userDetail;

                $name = $userDetail && $userDetail->first_name
                    ? $userDetail->first_name . ' ' . $userDetail->last_name
                    : $user->name;

                $profileImage = $userDetail ? $userDetail->profile_image : uploadedAsset('', 'profile');
                $jobTitle = $userDetail?->job_title;
                $country = $userDetail?->country
                    ? [
                        'name' => $userDetail->country->name,
                        'flag_image' => url('backend/assets/img/flags/' . strtolower($userDetail->country->code) . '.svg'),
                    ]
                    : null;

                return [
                    'user_id' => $user->id,
                    'name' => $name,
                    'total_gigs' => $buyerBookings->count(),
                    'profile_image' => $profileImage,
                    'job_title' => $jobTitle,
                    'country' => $country,
                ];
            })->values();

            $paginated = $buyerList->forPage($page, $perPage)->values();

            return [
                'code' => 200,
                'data' => $paginated,
                'next_page' => $buyerList->count() > $page * $perPage ? $page + 1 : null,
                'message' => __('web.common.default_retrieve_success'),
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'message' => __('web.common.default_retrieve_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array <string, mixed>
     */
    public function buyerFavoriteList(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = current_user();
        $userId = $authUser->id;
        $perPage = 3;

        // Load paginated wishlist with required relationships
        $wishlistQuery = Wishlist::with(['gigs.category'])
            ->where('user_id', $userId);

        if ($request->ajax()) {
            $wishlist = $wishlistQuery->paginate($perPage);
            $wishlistData = WishlistResource::collection($wishlist)->toArray($request);

            return [
                'ajax' => true,
                'code' => 200,
                'data' => $wishlistData,
                'next_page' => $wishlist->hasMorePages() ? $wishlist->currentPage() + 1 : null,
            ];
        }
        $wishlist = WishlistResource::collection($wishlistQuery->get())->toArray($request);
        return [
            'ajax' => false,
            'wishlist' => $wishlist,
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function getNotifications(Request $request): array
    {
        if (Auth::guard('web')->check()) {
            /** @var \App\Models\User $authUser */
            $authUser = Auth::guard('web')->user();
            $notificationModels = Notification::where('user_id', $authUser->id)->where('is_read', 0)->orderBy('created_at', 'desc')->limit(10)->get();
            $notifications = UserNotification::collection($notificationModels)->toArray($request);
            $notificationCount = Notification::where('user_id', $authUser->id)->where('is_read', 0)->count();
        } else {
            $notifications = [];
            $notificationCount = 0;
        }
        $html = view('frontend.common.notifications-popup', compact('notifications'))->render();
        return [
            'status' => 'success',
            'code' => 200,
            'html' => $html,
            'count' => $notificationCount,
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function markAllNotificationsAsRead(): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();

        $updated = Notification::where('user_id', $authUser->id)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        if ($updated > 0) {
            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('web.user.all_notifocations_marked_as_read'),
            ];
        }

        return [
            'status' => 'error',
            'code' => 404,
            'message' => __('web.user.no_new_notifications_found'),
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function sellerNotifications(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();
        $notifications = Notification::where('user_id', $authUser->id)->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            $view = view('frontend.seller.partials.notification-items', compact('notifications'))->render();

            return [
                'ajax' => true,
                'html' => $view,
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'prev_page_url' => $notifications->previousPageUrl(),
                'next_page_url' => $notifications->nextPageUrl(),
                'count' => $notifications->total(),
            ];
        }

        return [
            'ajax' => false,
            'notifications' => $notifications,
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function buyerNotifications(Request $request): array
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::guard('web')->user();
        $notifications = Notification::where('user_id', $authUser->id)->orderBy('created_at', 'desc')->paginate(10);
        if ($request->ajax()) {
            $view = view('frontend.seller.partials.notification-items', compact('notifications'))->render();

            return [
                'ajax' => true,
                'html' => $view,
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'prev_page_url' => $notifications->previousPageUrl(),
                'next_page_url' => $notifications->nextPageUrl(),
                'count' => $notifications->total(),
            ];
        }

        return [
            'ajax' => false,
            'notifications' => $notifications,
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function removeFavorite(int $serviceId): array
    {
        /** @var \App\Models\User $user */
        $user = current_user();

        $favorite = $user->wishlist()->where('service_id', $serviceId)->first();

        if (! $favorite) {
            return [
                'code' => 404,
                'message' => __('web.user.no_favourites_found'),
            ];
        }

        $favorite->delete();

        return [
            'code' => 200,
            'message' => __('web.user.favourite_removed_success'),
        ];
    }

    /**
     * @return array <string, mixed>
     */
    public function removeAllFavorites(): array
    {
        /** @var \App\Models\User $user */
        $user = current_user();
        $userId = $user->id;

        $deleted = Wishlist::where('user_id', $userId)->delete();

        if ($deleted) {
            return [
                'code' => 200,
                'success' => true,
                'message' => __('web.user.all_favourite_removed'),
            ];
        }
        return [
            'code' => 404,
            'success' => false,
            'message' => __('web.user.no_favourite_to_remove'),
        ];
    }
}
