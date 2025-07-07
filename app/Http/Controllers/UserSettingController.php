<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\UserSettingRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class UserSettingController extends Controller
{
    protected UserSettingRepositoryInterface $userSettingRepository;

    public function __construct(UserSettingRepositoryInterface $userSettingRepository)
    {
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * Display user settings page
     */
    public function userSettings(): View
    {
        $data = $this->userSettingRepository->getSettings();

        if (Route::currentRouteName() === 'buyer.settings') {
            return view('frontend.buyer.settings', $data);
        }

        return view('frontend.seller.settings', $data);
    }

    /**
     * Display user profile page
     */
    public function userProfile(): View
    {
        $data = $this->userSettingRepository->fetchUserProfile();

        if (Route::currentRouteName() === 'buyerprofile') {
            return view('frontend.buyer.buyerprofile', $data);
        }

        return view('frontend.seller.sellerprofile', $data);
    }

    /**
     * Save user profile/settings
     */
    public function saveProfile(Request $request): JsonResponse
    {
        $result = $this->userSettingRepository->updateSettings($request);

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }

    /**
     * Save account settings (PayPal, Stripe, Bank Transfer)
     */
    public function saveAccountSettings(Request $request): JsonResponse
    {
        $result = $this->userSettingRepository->saveAccountSettings($request);

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }

    /**
     * Delete user account
     */
    public function deleteAccount(): JsonResponse
    {
        $result = $this->userSettingRepository->deactivateAccount();

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $result = $this->userSettingRepository->changePassword($request);

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }

    /**
     * Upload profile image
     */
    public function uploadProfileImage(Request $request): JsonResponse
    {
        $result = $this->userSettingRepository->uploadProfileImage($request);

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }

    /**
     * Remove profile image
     */
    public function removeProfileImage(): JsonResponse
    {
        $result = $this->userSettingRepository->removeProfileImage();

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }

    /**
     * Get user devices
     */
    public function getUserDevices(): JsonResponse
    {
        $result = $this->userSettingRepository->getUserDevices();

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }

    /**
     * Logout device(s)
     */
    public function logoutDevice(Request $request): JsonResponse
    {
        $result = $this->userSettingRepository->logoutDevice($request);

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }
}
