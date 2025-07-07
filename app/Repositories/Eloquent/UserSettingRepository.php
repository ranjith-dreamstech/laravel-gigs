<?php

namespace App\Repositories\Eloquent;

use App\Models\AdditionalSetting;
use App\Models\Country;
use App\Models\User;
use App\Models\UserDetail;
use App\Repositories\Contracts\UserSettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\GeneralSetting\Models\UserDevice;

class UserSettingRepository implements UserSettingRepositoryInterface
{
    public function updateSettings(Request $request): array
    {
        $id = current_user()->id ?? $request->user_id;

        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,jpg,png|max:2048',
            'first_name' => 'required|min:3|max:20',
            'last_name' => 'required|max:20',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)->whereNull('deleted_at')],
            'phone_number' => ['required'],
            'dob' => ['required'],
            'gender' => ['required'],
            'country' => ['required'],
            'state' => ['required'],
            'city' => ['required'],
            'address' => ['required', 'max:150'],
        ]);

        if ($validator->fails()) {
            return [
                'status' => 'error',
                'code' => 422,
                'errors' => $validator->errors()->toArray(),
            ];
        }

        try {
            DB::beginTransaction();

            $userData = [
                'email' => $request->new_email ?? $request->email,
                'phone_number' => $request->phone_number,
            ];

            $userDetailsData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'country_id' => $request->country,
                'state_id' => $request->state,
                'city_id' => $request->city,
                'gender' => $request->gender,
                'address' => $request->address,
                'dob' => $request->dob,
                'job_title' => $request->job_title ?? null,
                'language_known' => $request->language_known ?? null,
                'tags' => $request->tags ?? null,
                'about' => $request->about ?? null,
                'profile_description' => $request->profile_description ?? null,
                'postal_code' => $request->postal_code ?? null,
                'skills' => $request->skills ?? null,
            ];

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $user = UserDetail::where('user_id', $id)->first();
                $oldImage = $user?->profile_image;
                $userDetailsData['profile_image'] = uploadFile($file, 'profile', $oldImage);
            }

            User::where('id', $id)->update($userData);
            UserDetail::updateOrCreate(['user_id' => $id], $userDetailsData);

            DB::commit();

            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('web.user.profile_update_success'),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'code' => 500,
                'message' => __('web.common.default_update_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getSettings(): array
    {
        $userId = current_user()->id;
        $user = User::with('userDetail')->find($userId);
        $countries = Country::where('status', 1)->get();
        $accountSettingsData = AdditionalSetting::where('user_id', $userId)->where('key', 'account_settings')->first();
        $accountSettings = $accountSettingsData ? json_decode($accountSettingsData->value, true) : [];

        return compact('countries', 'user', 'accountSettings');
    }

    public function fetchUserProfile(): array
    {
        $user = User::with('userDetail')->find(current_user()->id);
        return compact('user');
    }

    public function saveAccountSettings(Request $request): array
    {
        try {
            $id = current_user()->id ?? $request->user_id;
            $setting = AdditionalSetting::where('user_id', $id)->where('key', 'account_settings')->first();
            $existingData = $setting ? json_decode($setting->value, true) : [];

            if ($request->filled(['paypal_email', 'paypal_password'])) {
                $existingData['paypal'] = $request->only(['paypal_email', 'paypal_password']);
            }

            if ($request->filled(['stripe_email', 'stripe_password'])) {
                $existingData['stripe'] = $request->only(['stripe_email', 'stripe_password']);
            }

            if ($request->filled(['account_holder_name', 'bank_name'])) {
                $existingData['bank_transfer'] = $request->only(['account_holder_name', 'bank_name', 'ifsc_code', 'account_number']);
            }

            AdditionalSetting::updateOrCreate(
                ['user_id' => $id, 'key' => 'account_settings'],
                ['value' => json_encode($existingData, JSON_UNESCAPED_UNICODE)]
            );

            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('web.user.account_settings_update_success'),
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => __('web.common.default_update_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    public function deactivateAccount(): array
    {
        try {
            $id = current_user()->id;

            Session::flush();
            Auth::logout();

            User::where('id', $id)->delete();
            UserDetail::where('user_id', $id)->delete();

            return [
                'status' => 'success',
                'code' => 200,
                'message' => __('web.user.account_delete_success'),
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => __('web.common.default_delete_error'),
                'error' => $e->getMessage(),
            ];
        }
    }

    public function changePassword(Request $request): array
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', function ($value, $fail) {
                if (! Hash::check($value, current_user()->password)) {
                    $fail(__('The current password is incorrect.'));
                }
            },
            ],
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'Validation failed!',
                'errors' => $validator->errors()->toArray(),
            ];
        }

        try {
            User::where('id', current_user()->id)->update([
                'password' => Hash::make($request->new_password),
                'last_password_changed_at' => now(),
            ]);

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Password updated successfully!',
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => __('web.common.default_update_error'),
            ];
        }
    }

    public function uploadProfileImage(Request $request): array
    {
        // Placeholder, already handled in updateSettings()
        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Handled in profile update.',
        ];
    }

    public function removeProfileImage(): array
    {
        try {
            $userId = current_user()->id;
            $userDetail = UserDetail::where('user_id', $userId)->first();
            if ($userDetail && $userDetail->profile_image) {
                deleteFile($userDetail->profile_image);
                $userDetail->profile_image = null;
                $userDetail->save();
            }

            return [
                'status' => 'success',
                'code' => 200,
                'message' => 'Profile image removed successfully!',
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to remove profile image.',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getUserDevices(): array
    {
        $devices = UserDevice::where('user_id', current_user()->id)
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($device) {
                return [
                    'id' => $device->id,
                    'device_type' => $device->device_type,
                    'browser' => $device->browser,
                    'os' => $device->os,
                    'ip_address' => $device->ip_address,
                    'location' => $device->location,
                    'date' => formatDateTime($device->created_at),
                ];
            });

        return [
            'status' => 'success',
            'code' => 200,
            'data' => $devices,
        ];
    }

    public function logoutDevice(Request $request): array
    {
        try {
            $response = [];
            if ($request->is_all === 'true') {
                UserDevice::where('user_id', current_user()->id)->delete();
                $response = [
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'All devices removed successfully!',
                ];
            }else{
                $device = UserDevice::find($request->id);
                if ($device) {
                    $device->delete();
                    $response = [
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Device removed successfully!',
                    ];
                }

                $response = [
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Device not found.',
                ];
            }

        } catch (\Throwable $e) {
            $response = [
                'status' => 'error',
                'code' => 500,
                'message' => 'Failed to logout device.',
                'error' => $e->getMessage(),
            ];
        }

        return $response;
    }
}
