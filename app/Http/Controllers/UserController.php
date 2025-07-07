<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class UserController extends Controller
{
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function userProfile(): View
    {
        $data = $this->userRepository->fetchUserProfile();
        if (Route::currentRouteName() === 'buyerprofile') {
            return view('frontend.buyer.buyerprofile', $data);
        }
        return view('frontend.seller.sellerprofile', $data);
    }

    public function mySellers(): View
    {
        return view('frontend.buyer.my_sellers');
    }

    public function mySellerList(Request $request): JsonResponse
    {
        $response = $this->userRepository->mySellerList($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function myBuyerList(Request $request): JsonResponse
    {
        $response = $this->userRepository->myBuyerList($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function myBuyers(): View
    {
        return view('frontend.seller.my_buyers');
    }

    public function buyerFavoriteList(Request $request): JsonResponse|View
    {
        $response = $this->userRepository->buyerFavoriteList($request);
        if ($response['ajax']) {
            return response()->json($response, $response['code'] ?? 200);
        }
        $wishlist = $response['wishlist'];
        return view('frontend.buyer.favorite', compact('wishlist'));
    }

    public function getNotifications(Request $request): JsonResponse
    {
        $response = $this->userRepository->getNotifications($request);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function markAllNotificationsAsRead(): JsonResponse
    {
        $response = $this->userRepository->markAllNotificationsAsRead();
        return response()->json($response, $response['code'] ?? 200);
    }

    public function deleteAllNotifications(): JsonResponse
    {
        $user = Auth::guard('web')->user();
        if ($user) {
            Notification::where('user_id', $user->id)->delete();
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => __('web.user.all_notofocations_deleted'),
        ], 200);
    }

    public function sellerNotifications(Request $request): JsonResponse|View
    {
        $response = $this->userRepository->sellerNotifications($request);
        if ($response['ajax']) {
            return response()->json($response, 200);
        }
        $notifications = $response['notifications'];
        return view('frontend.seller.notifications', compact('notifications'));
    }

    public function buyerNotifications(Request $request): JsonResponse|View
    {
        $response = $this->userRepository->buyerNotifications($request);
        if ($response['ajax']) {
            return response()->json($response, 200);
        }
        $notifications = $response['notifications'];
        return view('frontend.buyer.notifications', compact('notifications'));
    }

    public function deleteNotification(Request $request): JsonResponse
    {
        Notification::where('id', $request->id)->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => __('web.user.notification_deleted'),
        ], 200);
    }

    public function markNotificationAsRead(Request $request): JsonResponse
    {
        Notification::where('id', $request->id)->update(['is_read' => 1]);
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => __('web.user.notification_marked_as_read'),
        ], 200);
    }

    public function removeFavorite(Request $request): JsonResponse
    {
        $response = $this->userRepository->removeFavorite($request->service_id);
        return response()->json($response, $response['code'] ?? 200);
    }

    public function removeAllFavorites(): JsonResponse
    {
        $response = $this->userRepository->removeAllFavorites();
        return response()->json($response, $response['code'] ?? 200);
    }
}
